<?php
class SponsorModel
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  /**
   * Get the sponsors from the database sorted by priority
   *
   * @return 
   */
  public function getSponsors()
  {
    $this->db->query("SELECT id, img_path, priority FROM sponsor ORDER BY priority");
    try {
      $sponsors = $this->db->getAll();
      return $sponsors;
    } catch (\Throwable $th) {
      return null;
    }
  }
}
