<?php

use Mollie\Api\Exceptions\ApiException;
use Mollie\Api\Exceptions\IncompatiblePlatform;

class CheckoutController extends Controller
{
    private $shoppingCart;
    private $checkoutModel;
    private $contentModel;
    private $eventModel;

    public function __construct()
    {
        // Custom css and javascript for the checkout pages
        $this->addCSS('checkout/checkout.css');
        $this->addJs('checkout/checkout.js');

        // Initialize the shopping cart
        $this->shoppingCart = new ShoppingCart();

        // Load the models for the current page
        $this->checkoutModel = $this->model('CheckoutModel');
        $this->contentModel = $this->model('ContentModel');
        $this->eventModel = $this->model('EventModel');

    }

    // Load Checkout Page
    public function index()
    {
        // Load custom page CSS
        $this->addCSS('checkout/shopping-cart.css');
        $this->addJs('jquery.min.js');
        $this->addJs('checkout/shopping-cart.js');

        // Get the unique days from the DB
        $uniqueDays = $this->eventModel->getUniqueDays();

        $data = [
            'program' => [
                'uniqueDays' => $uniqueDays,
                'items' => $this->shoppingCart->getItemsSorted($uniqueDays)
            ],
            'shoppingCart' => [
                'items' => $this->shoppingCart->getItems(),
                'totalPrice' => $this->shoppingCart->getTotalPrice()
            ]
        ];

        // Load homepage/index view
        $this->view('checkout/index', $data);
    }

    // Load checkout your information login page
    public function login($data = null)
    {
        // CSS & JS for payment page
        $this->addCSS('user-cards.css');
        // Check if it needs to add errors to the page, if null view clean login page
        if ($data == null) {
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',
            ];
        }
        // Require 'checkout login' view with $data array
        $this->view('checkout/login', $data);
    }

    // Load checkout your information register page
    public function register($data = null)
    {
        // CSS & JS for payment page
        $this->addCSS('user-cards.css');
        // Check if it needs to add errors to the page, if null view clean register page
        if ($data == null) {
            $data = [
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
        }
        // Require 'checkout register' view with $data array
        $this->view('checkout/register', $data);
    }

    // Load payment page
    public function payment(array $error = [])
    {
        if (isLoggedIn()) {
            // CSS & JS for 'order complete' page
            $this->addCSS('checkout/payment.css');
            $this->addJS('checkout/payment.js');
            // Get all data that is needed to be displayed on the payment page
            $data = [
                'tickets' => $this->shoppingCart->getItems(),
                'totalPrice' => $this->shoppingCart->getTotalPrice(),
                'content' => $this->contentModel->getContentForCategory(ContentCategory::CHECKOUT),
                'error' => $error
            ];
            // Require 'payment method' view with $data array
            $this->view('checkout/payment', $data);
        } else {
            redirect('checkout/login');
        }
    }

    /**
     * Check if the user is allowed to buy the tickets
     *
     * @param integer $paymentMethod , (1 = iDeal, 2 = CreditCard, 3 = PayPal, 4 = Invoice)
     */
    private function beforePaymentChecks($paymentMethod = null)
    {
        // Check if the user is not logged in
        if (!isLoggedIn()) {
            redirect('user/login');
        } // Check if the shopping cart is empty or if the request is not a post
        else if ($this->shoppingCart->isEmpty() || !isPost()) {
            redirect("checkout");
        } // Check if this is a credit card payment, and if so check if its above €2.000
        else if ($paymentMethod == 2) {
            if ($this->shoppingCart->getTotalPrice() > 2000) {
                redirect("checkout/payment");
            }
        }

        // Check if there is enough tickets available for this purchase
        $enoughStock = true;
        foreach ($this->shoppingCart->getItems() as $ticket) {
            if ($ticket['quantity'] > $this->checkoutModel->getTicketsByEventId($ticket['id'])->availability) {
                $enoughStock = false;
            }
        }
        if (!$enoughStock) {
            flash('alert', 'Sorry, there are not enough tickets to complete your order', 'alert');
            redirect('checkout');
        }
    }

    /**
     * Create a order in the database, add tickets and reserve the tickets for the user
     *
     * @param int $type payment type (1 = iDeal, 2 = CreditCard, 3 = PayPal, 4 = Invoice)
     * @return int $payment_id that is linked to the order
     * @throws Exception
     */
    private function createOrder(int $type)
    {
        // PDF location on server
        $location = 'invoice/invoice-' . $_SESSION['user_id'] . '-' . bin2hex(random_bytes(8)) . '.pdf';

        // Array with payment data
        $payment = [
            'price' => $this->shoppingCart->getTotalPrice(),
            'type' => $type,
            'status' => 'open',
            'invoice' => $location];

        // Add row to payment table in the database
        $payment_id = $this->checkoutModel->addPayment($payment);

        // Add row to booking table in the database
        $this->checkoutModel->addBooking($payment);

        // Add tickets to the booking_has_event table in the database
        $payment['tickets'] = $this->shoppingCart->getItems();
        $this->checkoutModel->addBookingEvent($payment);

        // Reserve tickets for buyer, (will be returned to the ticket pool if payment has failed or expired)
        $this->checkoutModel->removeStock($payment);

        // Create QR code for ticket and insert it into database (for future request for new tickets)
        $this->checkoutModel->addQRCode($payment_id, bin2hex(random_bytes(12)));

        // Fetch order from the database where the payment id matches
        $order = $this->checkoutModel->getOrderPayment($payment_id);

        // Create invoice PDF file
        createPDFInvoice($location, $this->shoppingCart->getItems(), $this->shoppingCart->getTotalPrice(), $order);

        return $payment_id;
    }

    /**
     * Pay the ticket with a chosen payment method
     *
     * @param $data
     * @return void
     * @throws ApiException
     * @throws IncompatiblePlatform
     */
    private function pay($data)
    {
        $mollie = new \Mollie\Api\MollieApiClient();
        $mollie->setApiKey("test_S5bvrqm4abfQh6efPP94eWV5PxkfGv");
        // Create mollie payment
        $payment = $mollie->payments->create([
            "amount" => [
                "currency" => "EUR",
                "value" => strval(number_format($this->shoppingCart->getTotalPrice(), 2, ".", ""))
            ],
            "method" => $data['method'],
            "description" => $data['description'],
            "redirectUrl" => URLROOT . '/checkout/complete?id=' . $data['id'],
            "webhookUrl" => URLROOT . '/checkout/webhook',
            "metadata" => [
                "order_id" => $data['id'],
            ],
            "issuer" => $data['issuer']
        ]);

        // Send the customer off to complete the payment.
        header("Location: " . $payment->getCheckoutUrl(), true, 303);
    }

    // User used ideal payment method to order tickets
    public function ideal()
    {
        // Perform pre payment checks
        $this->beforePaymentChecks();

        try {
            // Insert the order in the database and create a invoice PDF
            $payment_id = $this->createOrder(1);
        } catch (Exception $e) {
            flash('alert', 'Sorry, your order could not be created at this time. Try again in a couple of minutes.', 'alert');
            redirect('checkout');
        }

        // Create mollie payment
        try {
            $data = [
                'method' => \Mollie\Api\Types\PaymentMethod::IDEAL,
                'description' => "Haarlem Festival iDeal Order #{$payment_id}",
                'id' => $payment_id,
                "issuer" => !empty($_POST["issuer"]) ? $_POST["issuer"] : null
            ];
            $this->pay($data);
        } catch (IncompatiblePlatform $e) {
            echo "Incompatible platform: " . $e->getMessage();
        } catch (ApiException $e) {
            echo "API call failed: " . \htmlspecialchars($e->getMessage());
        }
    }

    // User used creditcard payment method to order tickets
    public function creditcard()
    {
        // Perform pre payment checks
        $this->beforePaymentChecks(2);

        try {
            // Insert the order in the database and create a invoice PDF
            $payment_id = $this->createOrder(2);
        } catch (Exception $e) {
            flash('alert', 'Sorry, your order could not be created at this time. Try again in a couple of minutes.', 'alert');
            redirect('checkout');
        }

        // Create mollie payment
        try {
            $data = [
                'method' => \Mollie\Api\Types\PaymentMethod::CREDITCARD,
                'description' => "Haarlem Festival Creditcard Order #{$payment_id}",
                'id' => $payment_id,
                "issuer" => null
            ];
            $this->pay($data);
        } catch (IncompatiblePlatform $e) {
            echo "Incompatible platform: " . $e->getMessage();
        } catch (ApiException $e) {
            echo "API call failed: " . \htmlspecialchars($e->getMessage());
        }
    }

    // User used paypal payment method to order tickets
    public function paypal()
    {
        // Perform pre payment checks
        $this->beforePaymentChecks();

        try {
            // Insert the order in the database and create a invoice PDF
            $payment_id = $this->createOrder(3);
        } catch (Exception $e) {
            flash('alert', 'Sorry, your order could not be created at this time. Try again in a couple of minutes.', 'alert');
            redirect('checkout');
        }

        // Create mollie payment
        try {
            $data = [
                'method' => \Mollie\Api\Types\PaymentMethod::PAYPAL,
                'description' => "Haarlem Festival Paypal Order #" . $payment_id,
                'id' => $payment_id,
                "issuer" => null
            ];
            $this->pay($data);
        } catch (IncompatiblePlatform $e) {
            echo "Incompatible platform: " . $e->getMessage();
        } catch (ApiException $e) {
            echo "API call failed: " . \htmlspecialchars($e->getMessage());
        }
    }

    // User used invoice payment method to order ticket
    public function invoice()
    {
        // Perform pre payment checks
        $this->beforePaymentChecks();

        // Filter post
        filterPost();

        $data = [
            'fname' => trim($_POST['fname']),
            'lname' => trim($_POST['lname']),
            'address' => trim($_POST['address']),
            'postcode' => trim($_POST['postcode']),
            'city' => trim($_POST['city']),
            'phonenumber' => trim($_POST['phonenumber']),
            'fnameError' => '',
            'lnameError' => '',
            'addressError' => '',
            'postcodeError' => '',
            'cityError' => '',
            'phonenumberError' => ''];

        // Validate user inputted data
        $data['fnameError'] = validateString($data['fname'], 'first name');
        $data['lnameError'] = validateString($data['lname'], 'last name');
        $data['addressError'] = validateString($data['address'], 'address');
        $data['postcodeError'] = validateString($data['postcode'], 'postcode');
        $data['cityError'] = validateString($data['city'], 'city');
        $data['phonenumberError'] = validatePhoneNumber($data['phonenumber']);

        if (empty($data['fnameError']) && empty($data['lnameError']) && empty($data['addressError']) && empty($data['postcodeError']) && empty($data['cityError']) && empty($data['phonenumberError'])) {
            try {
                // Insert the order in the database and create a invoice PDF
                $pay_id = $this->createOrder('4');

                // Insert the invoice data in the database
                $this->checkoutModel->addInvoice($data, $pay_id);
            } catch (Exception $e) {
                flash('alert', 'Sorry, your order could not be created at this time. Try again in a couple of minutes.', 'alert');
                redirect('checkout');
            }

            // Empty shopping cart
            $this->shoppingCart->clear();

            try {
                // Fetch order from the database where the payment id matches
                $order = $this->checkoutModel->getOrderPayment($pay_id);
            }
            catch (Throwable $th) {
                die('Payment details could not be fetched from the database');
            }

            // Path to the invoice PDF on the server
            $pdfWithInvoicePath = URLROOT . '/' . $order->invoice;

            // Get the current logged in user
            $userModel = $this->model('UserModel');
            sendInvoiceConfirmationEmail($userModel->getUserById($_SESSION['user_id']), $pdfWithInvoicePath);

            // Go to complete page with success message
            redirect('checkout/complete?id=' . $pay_id);
        } else {
            // Give the user the invalid input errors on the payment method page
            $this->payment($data);
        }
    }

    public function webhook()
    {
        try {
            $mollie = new \Mollie\Api\MollieApiClient();
            $mollie->setApiKey("test_S5bvrqm4abfQh6efPP94eWV5PxkfGv");
            $payment = $mollie->payments->get($_POST["id"]);

            $payment_id = $payment->metadata->order_id;
            // Update order status
            $this->checkoutModel->updateStatus($payment_id, $payment->status);
            if ($payment->isPaid() && !$payment->hasRefunds() && !$payment->hasChargebacks()) {
                // Fetch order from the database where the payment id matches
                $order = $this->checkoutModel->getOrderPayment($payment_id);

                // Path to the invoice PDF on the server
                $pdfWithInvoicePath = URLROOT . '/' . $order->invoice;

                // Send the confirmation mail to the user
                sendOrderConfirmationEmail($this->checkoutModel->getUserByPaymentId($payment_id), $pdfWithInvoicePath);
            } elseif ($payment->isOpen()) {
                $this->failedPayment($payment_id);
            } elseif ($payment->isPending()) {
                // The payment is pending.
            } elseif ($payment->isFailed()) {
                $this->failedPayment($payment_id);
            } elseif ($payment->isExpired()) {
                $this->failedPayment($payment_id);
            } elseif ($payment->isCanceled()) {
                $this->failedPayment($payment_id);
            } elseif ($payment->hasRefunds()) {
                $this->failedPayment($payment_id);
            } elseif ($payment->hasChargebacks()) {
                $this->failedPayment($payment_id);
            }
        } catch (ApiException $e) {
            echo "API call failed: " . \htmlspecialchars($e->getMessage());
        } catch (Exception $e) {
            echo 'Random Bytes failed: ' . $e->getMessage();
        }
    }

    private function failedPayment($payment_id)
    {
        // Payment failed, or refunded. Returning tickets to ticket pool
        $this->checkoutModel->addStock($this->checkoutModel->getTicketsByPaymentId($payment_id));
    }

    // Load order complete page
    public function complete()
    {
        $data = [];

        // CSS & JS for 'order complete' page
        $this->addCSS('checkout/complete.css');

        // Wait 1 second for database to update
        sleep(1);

        // Check if id is given in the url
        if (isset($_GET["id"])) {
            try {
                // Fetch order from the database where the payment id matches
                $order = $this->checkoutModel->getOrderPayment($_GET["id"]);
                $data['order'] = $order;
            }
            catch (Throwable $th) {
                die('Payment could not be fetched from the database');
            }

            if ($order->status == 'paid') {
                // Clear shopping cart
                $this->shoppingCart->clear();
            }
        }

        // Require 'order complete' view php file
        $this->view('checkout/complete', $data);
    }
}
