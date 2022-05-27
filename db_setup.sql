CREATE TABLE `admins` (
  `admin_id` serial,
  `email` varchar(255),
  `pswd_hash` varchar(255),
  PRIMARY KEY (`admin_id`)
);

CREATE TABLE `users` (
  `user_id` serial,
  `email` varchar(255)  ,
  `first_name` varchar(255),
  `last_name` varchar(255),
  `dob` date,
  `pswd_hash` varchar(255),
  `register_date` date,
  `profile_pic` varchar(255),
  `gender` int,
  PRIMARY KEY (`user_id`)
);

CREATE TABLE `restaurants` (
  `rest_id` serial,
  `rest_name` varchar(255),
  `rest_desc` text,
  `rest_pic` blob,
  PRIMARY KEY (`rest_id`)
);

CREATE TABLE `reviews` (
  `review_id` serial,
  `user_id` int,
  `rest_id` int,
  `review_content` text,
  `rate` int,
  `status` int,
  PRIMARY KEY (`review_id`)
);

