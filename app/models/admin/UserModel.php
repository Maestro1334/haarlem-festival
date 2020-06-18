<?php

class UserModel
{
  private $db;

  public function __construct()
  {
      $this->db = new Database;
  }


  public function getAllUsers()
  {
    $this->db->query('SELECT id, email, type FROM user');
    $users = $this->db->getAll();
    return $users;
  }

  /**
   * Delete the user with id
   * 
   * @param string $user_id Id of the to find user
   * @return bool true if succeed
   */
  public function deleteUser($user_id)
  {
    $this->db->query('DELETE FROM user WHERE id = :id');
    $this->db->bind(':id', $user_id);

    if ($this->db->execute()) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Add the the user with data
   * 
   * @param array $data Data form the to add to the db
   * @return bool true if succeed
   */
  public function updateUser($data)
  {
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

  public function addUser($data)
  {
    $this->db->query('INSERT INTO user (email, password, type) VALUES(:email, :password, :type)');

    $this->db->bind(':email', $data['email']);
    $this->db->bind(':password', $data['hashed_password']);
    $this->db->bind(':type', $data['type']);

    if ($this->db->execute()) {
      return true;
    } else {
      return false;
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
   * Find the user by id
   * 
   * @param string $id Id of the to find user
   * @return UserModel $user The user if found
   */
  public function getUserById($id)
  {
    $this->db->query('SELECT id, email, password, type  FROM user WHERE id = :id');
    $this->db->bind(':id', $id);

    try {
      $user = $this->db->getSingle();
      return $user;
    } catch (\Throwable $th) {
      throw $th;
    }
    }

    /**
   * Get user and authenticate with password
   * 
   * @param $email email to log in with
   * @param $password password to authenticate
   * @return user or boolean false
   */
  public function login($email, $password)
  {
    $this->db->query('SELECT id, email, password, type FROM user WHERE email = :email');
    $this->db->bind(':email', $email);
    try {
      $row = $this->db->getSingle();
    } catch (\Throwable $th) {
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
   * Find the user by email
   * 
   * @param string $email Email address of the to find user
   * @return UserModel $user The user if found
   */
  public function getUserByEmail($email)
  {
    $this->db->query('SELECT id, email, password FROM user WHERE email = :email');
    $this->db->bind(':email', $email);
    try {
      $user = $this->db->getSingle();
      return $user;
    } catch (\Throwable $th) {
      throw $th;
    }
  }
}



?>