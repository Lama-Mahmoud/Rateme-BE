<?php
include_once("models/Database.php");

class Review extends Database
{
    private $table = "reviews";

    public function createReview($user_id, $rest_id, $review_content, $rate, $status = 2)
    {
        $query_string = "INSERT INTO $this->table 
                        (user_id, rest_id, review_content, rate, status) 
                        VALUES (?, ?, ?, ?, ?)";
        $validation_string = "iisii";
        $params = [$user_id, $rest_id, $review_content, $rate, $status];
        $result = $this->query($query_string, $validation_string, $params);
        return $result["affected_rows"];
    }
    public function updateReviewStatus($review_id, $new_status)
    {
        $query_string = "UPDATE $this->table 
                        SET status = ?
                        WHERE review_id = ?";
        $validation_string = "ii";
        $params = [$new_status, $review_id];
        $result = $this->query($query_string, $validation_string, $params);
        return $result["affected_rows"];
    }
    public function getAcceptedRestaurantReviews($rest_id)
    {
        $query_string = "SELECT user_id, review_content, rate
                        FROM $this->table
                        WHERE rest_id = ? and status = 1";
        $validation_string = "i";
        $params = [$rest_id];
        $result = $this->getRows($query_string, $validation_string, $params);
        return $result;
    }
    public function getReviews()
    {
        // constructing a query string that join multiple tables
        $query_string = "SELECT r.review_id, u.email, rest.rest_name, r.review_content, r.rate, r.status
                        FROM $this->table r, users u, restaurants rest
                        WHERE u.user_id = r.user_id AND rest.rest_id = r.rest_id
                        ORDER BY r.status DESC";
        $result = $this->getRows($query_string);
        return $result;
    }
}
