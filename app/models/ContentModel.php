<?php
class ContentModel
{
  private $db;

  public function __construct()
  {
    $this->db = new Database();
  }

  /**
   * Get the content by a given reference from the database
   *
   * @param string $reference Reference string for the content
   * @return string $value Value of the content
   */
  public function getContentByReference($reference)
  {
    $this->db->query("SELECT value FROM content WHERE reference = :reference");
    $this->db->bind(':reference', $reference);

    try {
      $value = $this->db->getSingle()->value;
      return $value;
    } catch (\Throwable $th) {
      return 'NO CONTENT FOUND FOR REFERENCE: ' . $reference;
    }
  }

  /**
   * Get the content for the full category
   * 
   * @param string $category Category for the content to get. Use ContenCategory::[category]
   * @return array $content All content for the category
   */
  public function getContentForCategory($category)
  {
    if ($this->isContentCategory($category)) {
      $this->db->query("SELECT reference, value FROM content WHERE reference LIKE :category");
      $this->db->bind(':category', $category . '%');

      try {
        $contents = $this->db->getAll();

        $sortedContent = [];
        foreach($contents as $content){
            $sortedContent[$content->reference] = $content->value;
        }

        return $sortedContent;
      } catch (\Throwable $th) {
        return null;
      }
    } else {
      return null;
    }
  }

  /**
   * Check if the given category is a valid ContentCategory
   * 
   * @param string $category Category to check
   * @return bool
   */
  private function isContentCategory($category)
  {
    $categories = ContentCategory::getConstants();
    return (array_search($category, $categories)) ? true : false;
  }
}
