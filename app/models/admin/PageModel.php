<?php

class PageModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function updateContent($reference, $content)
    {
        $this->db->query('UPDATE content SET value = :value WHERE reference = :reference');
        
        $this->db->bind(':value', $content);
        $this->db->bind(':reference', $reference);

        if ($this->db->execute()) {
            return true;
          } else {
            return false;
          }
    }
    
    public function getProgram($category)
    {
        $category = strtoupper($category);

        switch ($category) {
            case 'HISTORIC':
                $this->db->query('SELECT id, name, category, date, start_time, end_time, location FROM event JOIN event_day ON id = event_id WHERE category = :category AND name LIKE :singleTour ORDER BY date, start_time ASC');
                $this->db->bind(':singleTour', 'Historic Tour Single%');
                break;
            case 'JAZZ':
                $this->db->query('SELECT id, name, category, date, start_time, end_time, location FROM event JOIN event_day ON id = event_id WHERE category = :category ORDER BY date, location, start_time ASC');
                break;
            default:
                $this->db->query('SELECT id, name, category, date, start_time, end_time, location FROM event JOIN event_day ON id = event_id WHERE category = :category ORDER BY date, start_time ASC');
                break;
        }
        
        $this->db->bind(':category', $category);

        try {
            $program = $this->db->getAll();
            return $program;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function addEvent($data)
    {
        $category = strtoupper($data['title']);

        if ($category == 'HISTORIC') {
            $this->db->query('INSERT INTO event (name, category, start_time, end_time, location) VALUES (:name, :category, :start, :end, :location); ');
            $this->db->bind(':name', $data['name'] . ' Single ' . $data['language']);
            $this->db->bind(':category', $category);
            $this->db->bind(':start', $data['start_time']);
            $this->db->bind(':end', $data['end_time']);
            $this->db->bind(':location', $data['location']);
            if ($this->db->execute()){
                $event_id = $this->db->lastInsertId();
                $this->db->query('INSERT INTO event_day (event_id, date) VALUES (:event_id, :date);');
                $this->db->bind(':event_id', $event_id);
                $this->db->bind(':date', $data['date']);
                if (!$this->db->execute()){
                    return false;
                }
            } else {
                return false;
            }
            $data['name'] = $data['name'] . 'Family ' . $data['language'];
        }

        $this->db->query('INSERT INTO event (name, category, start_time, end_time, location) VALUES (:name, :category, :start, :end, :location); ');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':category', $category);
        $this->db->bind(':start', $data['start_time']);
        $this->db->bind(':end', $data['end_time']);
        $this->db->bind(':location', $data['location']);

        if ($this->db->execute()) {
            $event_id = $this->db->lastInsertId();
            $this->db->query('INSERT INTO event_day (event_id, date) VALUES (:event_id, :date);');
            $this->db->bind(':event_id', $event_id);
            $this->db->bind(':date', $data['date']);
            if (!$this->db->execute()){
                    return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function updateEvent($data)
    {
        $category = strtoupper($data['title']);

        if ($category == 'HISTORIC') {
            $this->db->query('UPDATE event SET name = :name, start_time = :start, end_time = :end, location = :location WHERE id = :id; UPDATE event_day SET date = :date WHERE event_id = :id');
            $this->db->bind(':name', $data['name'] . ' Single ' . $data['language']);
            $this->db->bind(':category', $category);
            $this->db->bind(':start', $data['start_time']);
            $this->db->bind(':end', $data['end_time']);
            $this->db->bind(':location', $data['location']);
            $this->db->bind(':id', $data['id']);
            $this->db->bind(':date', $data['date']);
            if (!$this->db->execute()){
                return false;
            }
            $data['name'] = $data['name'] . ' Family ' . $data['language'];
            $data['id']++;
        }
        $this->db->query('UPDATE event SET name = :name, start_time = :start, end_time = :end, location = :location WHERE id = :id; UPDATE event_day SET date = :date WHERE event_id = :id');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':start', $data['start_time']);
        $this->db->bind(':end', $data['end_time']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':date', $data['date']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteEvent($id, $category)
    {
        $category = strtolower($category);

        $this->db->query('DELETE FROM event_day WHERE event_id = :id; DELETE FROM event WHERE id = :id;');
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            if ($category == 'historic') {
                $id++;
                $this->db->query('DELETE FROM event_day WHERE event_id = :id; DELETE FROM event WHERE id = :id;');
                $this->db->bind(':id', $id);
                if ($this->db->execute()) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function getEventById($id)
    {
        $this->db->query('SELECT name, category, date, start_time, end_time, location FROM event JOIN event_day ON id = event_id WHERE id = :id');
        $this->db->bind(':id', $id);

        try {
            $event = $this->db->getSingle();
            return $event;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getLineUp($category)
    {
        $category = strtoupper($category);
        switch ($category) {
            case 'HISTORIC':
                $this->db->query('SELECT id, name FROM artists WHERE category = :category');
                break;
            case 'FOOD':
                $this->db->query('SELECT DISTINCT restaurant.id as id, name FROM event_restaurant JOIN event ON event_id = event.id JOIN restaurant ON restaurant_id = restaurant.id');
                break;
            default:
                $this->db->query('SELECT id, name, img_path FROM artists WHERE category = :category');
                break;
        }
        $this->db->bind(':category', $category);
        try {
            $artists = $this->db->getAll();
            return $artists;
        } catch (\Throwable $th) {
            throw $th;
        }
        
    }

    public function addArtist($data)
    {
        $category = strtoupper($data['title']);

        switch ($category) {
            case 'FOOD':
                $this->db->query('INSERT INTO restaurant (type, short_description, long_description, img_path, price) VALUES(:type, :short_des, :long_des, :img_path, :price)');
                $this->db->bind(':type', $data['res_type']);
                $this->db->bind(':short_des', $data['short_des']);
                $this->db->bind(':long_des', $data['long_des']);
                $this->db->bind(':img_path', $data['img_path']);
                $this->db->bind(':price', 0);
                break;
            case 'HISTORIC':
                $this->db->query('INSERT INTO artists(category, name) VALUES(:category, :name)');
                $this->db->bind(':category', $category);
                $this->db->bind(':name', $data['name']);
                break;
            default:
                $this->db->query('INSERT INTO artists(category, name) VALUES(:category, :name)');
                $this->db->bind(':category', $category);
                $this->db->bind(':name', $data['name']);
                break;
        }
        

        if ($this->db->execute()) {
            if ($category == 'FOOD') {
                $res_id = $this->db->lastInsertId();
                for ($i=0; $i < count($data['allergen']); $i++) { 
                    $this->db->query('INSERT INTO restaurant_has_allergen (restaurant_id, allergen_id) SELECT :res_id, id FROM allergen WHERE name =:name');
                    $this->db->bind(':res_id', $res_id);
                    $this->db->bind(':name', $data['allergen'][$i]);
                    if(!$this->db->execute()){
                        return false;
                    }
                }
                $this->db->query('INSERT INTO event(name, category) VALUES(:name, :category)');
                $this->db->bind(':name', $data['name']);
                $this->db->bind(':category', $category);
                if ($this->db->execute()) {
                    $event_id = $this->db->lastInsertId();
                    $this->db->query('INSERT INTO event_restaurant(event_id, restaurant_id) VALUES(:event, :res)');
                    $this->db->bind(':event', $event_id);
                    $this->db->bind(':res', $res_id);
                    if (!$this->db->execute()) {
                        return false;
                    }
                }
            } else {
                return true;
            }
        } else {
            return false;
        }
        
    }

    public function deleteArtist($id)
    {
        $this->db->query('DELETE FROM artists WHERE id = :id');
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getArtistById($id, $category)
    {
        $category = strtoupper($category);

        switch ($category) {
            case 'HISTORIC':
                $this->db->query();
                break;
            case 'FOOD':
                $this->db->query('SELECT DISTINCT name, category, location, type, long_description, short_description, img_path FROM event_restaurant JOIN event ON event_id = event.id JOIN restaurant ON restaurant_id = restaurant.id WHERE restaurant.id = :id');
                break;
            
            default:
                $this->db->query('SELECT category, name, img_path FROM artists WHERE id = :id');
                break;
        }
        $this->db->bind(':id', $id);

        try {
            $artist = $this->db->getSingle();
            return $artist;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getAllergenFromRestaurant($id)
    {
        $this->db->query('SELECT name FROM restaurant_has_allergen JOIN allergen ON allergen_id = allergen.id WHERE restaurant_id = :id');
        $this->db->bind(':id', $id);

        try {
            $allergen = $this->db->getAll();
            return $allergen;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function updateArtist($data)
    {
        $category = strtoupper($data['title']);

        switch ($category) {
            case 'HISTORIC':
                $this->db->query();
                break;
            case 'FOOD':
                $this->db->query('UPDATE restaurant SET short_description = :short_des, long_description = :long_des, img_path = :img_path WHERE id = :id');
                
                $this->db->bind(':short_des', $data['short_des']);
                $this->db->bind(':long_des', $data['long_des']);
                $this->db->bind(':img_path', $data['img_path']);
                break;
            
            default:
                $this->db->query('UPDATE artists SET name = :name, img_path = :img_path WHERE id = :id');
                $this->db->bind(':name', $data['name']);
                $this->db->bind(':img_path', $data['img_path']);
                break;
        }
        $this->db->bind(':id', $data['id']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getSponsors()
    {
        $this->db->query('SELECT id, name FROM sponsor ORDER BY priority DESC');

        try {
            $sponsors = $this->db->getAll();
            return $sponsors;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function getSponsorById($id)
    {
        $this->db->query('SELECT id, name, img_path, priority FROM sponsor WHERE id = :id');
        $this->db->bind(':id', $id);

        try {
            $sponsor = $this->db->getSingle();
            return $sponsor;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function deleteSponsor($id)
    {
        $this->db->query('DELETE FROM sponsor WHERE id = :id');
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function addSponsor($data)
    {
        $this->db->query('INSERT INTO sponsor(name, img_path, priority) VALUES(:name, :img_path, :priority)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':img_path', $data['img_path']);
        $this->db->bind(':priority', $data['priority']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function updateSponsor($data)
    {
        $this->db->query('UPDATE sponsor SET name = :name, img_path = :img_path, priority = :priority WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':img_path', $data['img_path']);
        $this->db->bind(':priority', $data['priority']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}

?>