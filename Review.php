<?php
include_once("Database.php");

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
    public function updateReviewStatus()
    {
    }
    public function getRestaurantReviews()
    {
    }
    public function getReviews()
    {
    }
}
