--
-- Dumping data for table `acca_site_config`
--
truncate acca_site_config;
INSERT INTO `acca_site_config` (`id`, `client_name`,`city_id`) VALUES
(1, '-', 104);

--
-- Dumping data for table `acca_user`
--
truncate acca_user;
INSERT INTO `acca_user` (`id`, `username`, `email`, `password`, `code`, `name`, `city_id`, `address`, `phone`, `created`, `created_user_id`, `modified`, `enabled`, `avatar_img`, `roles_id`) VALUES
(65, 'admin', '-', '63c8f0854166152eaacc876088a8e6b1729ca2d7', '-', 'Administrator', 2, '-', '-', NULL, NULL, NULL, 1, '', '-1');
