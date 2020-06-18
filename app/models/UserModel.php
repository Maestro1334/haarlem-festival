<?php
class UserModel
{
    private $db;
    public function __construct()
    {
        $this->db = new Database;
    }
    
    /**
     * Register a new user
     *
     * @param $data Email and password for the user
     * @param int $type Enum value of the user type
     * @return bool
     * @throws Exception
     */
    public function register($data, $type = 1)
    {
        // Prepare Query
        $this->db->query('INSERT INTO user (email, password, type) VALUES(:email, :password, :type)');
        // Bind Values
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':type', $type);
        try {
            $this->db->execute();
            return true;
        } catch (Throwable $th) {
            throw new Exception('Registering user failed: ' . $th->getMessage());
        }
    }

    /**
     * Get user and authenticate with password
     *
     * @param $email email to log in with
     * @param $password password to authenticate
     * @return user or boolean false
     * @throws Throwable
     */
    public function login($email, $password){
        $this->db->query('SELECT id, email, password, type FROM user WHERE email = :email');
        $this->db->bind(':email', $email);
        try {
            $row = $this->db->getSingle();
        } catch (Throwable $th) {
            throw $th;
        }

        $hashed_password = $row->password;
        if (password_verify($password, $hashed_password)) {
            return $row;
        } else {
            return false;
        }
    }

    /**
     * Update the password of the user
     *
     * @param string $password The new not hashed password
     * @param int $user_id The id of the user to update
     */
    public function updateUserPassword(string $password, int $user_id){
        $this->db->query('UPDATE user SET password = :password  WHERE id = :id');
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->db->bind(':id', $user_id);
        $this->db->bind(':password', $hashedPassword);
        try {
            $this->db->execute();
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * Get the user by their id
     *
     * @param int $user_id Id of the user to retrieve
     * @return $user User data from database
     */
    public function getUserById(int $user_id){
        $this->db->query("SELECT id, email, password, type  FROM user WHERE id = :id");
        $this->db->bind(':id', $user_id);
        try {
            $user = $this->db->getSingle();
            return $user;
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * Find the user by email
     *
     * @param string $email Email address of the to find user
     * @return UserModel $user The user if found
     * @throws Throwable
     */
    public function getUserByEmail($email){
        $this->db->query("SELECT id, email, password FROM user WHERE email = :email");
        $this->db->bind(':email', $email);
        try {
            $user = $this->db->getSingle();
            return $user;
        } catch (Throwable $th) {
            throw $th;
        }
    }

    /**
     * Check if there is a user with this email address
     *
     * @param string $email Email address to check on
     * @return int exists with value 1 or 0
     */
    public function isUser(string $email){
        $this->db->query("SELECT EXISTS(SELECT email FROM user WHERE email = :email LIMIT 1) AS `exists`");
        $this->db->bind(':email', $email);
        return $this->db->getSingle();
    }

    /**
     * Get the id of the user by email
     *
     * @param string $email Email of the to retrieving user
     * @return int $id Id of the user or null
     */
    public function getUserIdByEmail(string $email){
        $this->db->query("SELECT id FROM user WHERE email = :email");
        $this->db->bind(':email', $email);
        try {
            $result = $this->db->getSingle();
            return $result->id;
        } catch (\Throwable $th) {
            return null;
        }
    }

    /**
     * Create a row in the database for the forgot password token
     *
     * @param string $email Email address of the user
     * @param string $token Reset token to be saved
     * @throws Throwable
     */
    public function createPasswordReset(string $user_id, string $token){
        // Delete previous tokens before saving a new one
        try {
            $this->deletePasswordResetToken($user_id);
        } catch (Throwable $th) {
            throw $th;
        }
        $this->db->query("INSERT INTO password_reset_token (user_id, expire_date, token) VALUES (:user_id, :expire_date, :token)");
        $expire_date = new DateTime();
        $expire_date->add(new DateInterval('PT12H'));
        $this->db->bind(':user_id', $user_id);
        $this->db->bind(':expire_date', $expire_date->format('Y-m-d H:i:s'));
        $this->db->bind(':token', $token);
        try {
            $this->db->execute();
        } catch (Throwable $th) {
            throw new Exception('Creating the token went wrong: ' . $th->getMessage());
        }
    }
    /**
     * Delete the database row for the given user_id
     *
     * @param string $user_id Id of the user
     */
    public function deletePasswordResetToken(string $user_id){
        $this->db->query("DELETE FROM password_reset_token WHERE user_id = :user_id");
        $this->db->bind(':user_id', $user_id);
        try {
            $this->db->execute();
        } catch (Throwable $th) {
            throw new Exception('Deleting password tokens failed: ' . $th->getMessage());
        }
    }

    /**
     * Update the password after filling in the password reset
     *
     * @param string $password The new password
     * @param string $token The token of the password reset
     */
    public function updatePasswordFromReset(string $password, string $token){
        $this->db->query("SELECT user_id, token FROM password_reset_token WHERE token = :token");
        $this->db->bind(':token', $token);
        $row = $this->db->getSingle();
        if ($row) {
            try {
                $this->updateUserPassword($password, $row->user_id);
                $this->deletePasswordResetToken($row->user_id);
            } catch (Throwable $th) {
                throw new Exception('Updating the password from reset failed: ' . $th->getMessage());
            }
        } else {
            throw new Exception('There is no user for this token');
        }
    }

    /**
     * Get the user id linked with the token
     *
     * @param string $token Token to get the linkes user id from
     * @return int User id or null
     */
    public function getTokenUserId(string $token){
        $this->db->query("SELECT user_id FROM password_reset_token WHERE token = :token");
        $this->db->bind(':token', $token);
        try {
            $row = $this->db->getSingle();
            return $row->user_id;
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function getAllUsers(){
        $this->db->query('SELECT id, email, type FROM user');
        $users = $this->db->getAll();
        return $users;
    }

    public function deleteUser($user_id){
        $this->db->query('DELETE FROM user WHERE id = :id');
        $this->db->bind(':id', $user_id);
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateUser($data){
        $this->db->query('UPDATE user SET email = :email, type = :type WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':type', $data['type']);
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function addUser($data){
        $this->db->query('INSERT INTO user (email, password, type) VALUES(:email, :password, :type)');
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':type', $data['type']);
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method to get the role of a user
     *
     * @param $userId
     * @return |null
     */
    public function getUserRole($userId){
        $this->db->query("SELECT type FROM user WHERE id = :id;");
        $this->db->bind(":id", $userId);

        try {
            return $this->db->getSingle()->type;
        } catch (\Throwable $th) {
            return null;
        }
    }

  /**
   * Get user invoice and qrcode
   *
   * @return array of invoice(s) and qrcode(s)
   * @throws Throwable
   */
  public function getUserOrders()
  {
    $this->db->query('SELECT invoice FROM payment JOIN booking ON payment_id = payment.id WHERE user_id = :id ');
    $this->db->bind(':id', $_SESSION['user_id']);

    try {
      return $this->db->getAll();
    } catch (\Throwable $th) {
      return null;
    }
  }
}
