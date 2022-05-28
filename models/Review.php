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
    public function getRestaurantReviews()
    {
    }
    public function getReviews()
    {
    }
}