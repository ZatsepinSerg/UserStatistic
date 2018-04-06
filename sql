ALTER TABLE `main__users` ADD `visit_status` INT NULL DEFAULT '0' AFTER `token`;
ALTER TABLE `main__users` ADD `visit_date`  timestamp NULL DEFAULT NULL AFTER `visit_status`;
ALTER TABLE `main__users` ADD `visit_basket` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `visit_date`;

CREATE TABLE `main__statistic_user` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `search_string` text NOT NULL,
  `product_id` int(11) NOT NULL,
  `fail_login` varchar(255) NOT NULL,
  `fail_password` varchar(255) NOT NULL,
  `visit` int(11) NOT NULL DEFAULT '0',
  `visit_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `main__statistic_user`
  ADD PRIMARY KEY (`id`);
ALTER TABLE `main__statistic_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
