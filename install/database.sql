DROP DATABASE IF EXISTS `{{dbname}}`;

CREATE DATABASE `{{dbname}}`;

USE `{{dbname}}`;

CREATE TABLE `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `login` varchar(32) NOT NULL,
  `firstname` varchar(32) NOT NULL,
  `lastname` varchar(32) NOT NULL,
  `birthday` date NOT NULL,
  `email` varchar(140) NOT NULL,
  `phonenumber` varchar(32) NOT NULL,
  `status` enum('inactive','active') DEFAULT 'active',
  `type` enum('superuser', 'administrator','editor','user') NOT NULL,
  `hash` varchar(64) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARSET = utf8;

INSERT INTO `users` (`login`, `firstname`, `lastname`, `birthday`, `email`, `phonenumber`, `type`, `hash`) VALUES
('superuser', 'Джеф', 'Армстронг', '1971-8-12', 'jeff.armstrong12@example.com', '(808)-499-6292', 'superuser', 'e0bc60c82713f64ef8a57c0c40d02ce24fd0141d5cc3086259c19b1e62a62bea'),
('leta.black', 'Лета', 'Блэк', '1970-12-23', 'leta.black12@example.com', '(583)-279-3776', 'administrator', 'e0bc60c82713f64ef8a57c0c40d02ce24fd0141d5cc3086259c19b1e62a62bea'),
('shelly.hopkins', 'Шелли', 'Хопкинс', '1974-9-19', 'shelly.hopkins22@example.com', '(407)-686-5758', 'editor', 'e0bc60c82713f64ef8a57c0c40d02ce24fd0141d5cc3086259c19b1e62a62bea'),
('allison.ryan', 'Эллисон', 'Раен', '1983-7-8', 'allison.ryan37@example.com', '(900)-487-6041', 'editor', 'e0bc60c82713f64ef8a57c0c40d02ce24fd0141d5cc3086259c19b1e62a62bea'),
('jim.clark', 'Джим', 'Кларк', '1979-1-20', 'jim.clark92@example.com', '(464)-949-9206', 'user', 'e0bc60c82713f64ef8a57c0c40d02ce24fd0141d5cc3086259c19b1e62a62bea'),
('jordan.roberts', 'Джордан', 'Робертс', '1976-9-16', 'jordan.roberts85@example.com', '(571)-838-8238', 'user', 'e0bc60c82713f64ef8a57c0c40d02ce24fd0141d5cc3086259c19b1e62a62bea'),
('adrian.thomas', 'Адриан', 'Томас', '1985-7-10', 'adrian.thomas77@example.com', '(786)-810-8778', 'user', 'e0bc60c82713f64ef8a57c0c40d02ce24fd0141d5cc3086259c19b1e62a62bea');

CREATE TABLE `permissions` (
  `pid` int(10) NOT NULL AUTO_INCREMENT,
  `p_desc` varchar(150) NOT NULL,
  `p_name` VARCHAR(100) NOT NULL,
  `p_type` INT(1),
  `usertype` enum('superuser','administrator','editor','user') NOT NULL,
  PRIMARY KEY (pid)
);

INSERT INTO `permissions` (`p_desc`, `p_name`, `p_type`, `usertype`) VALUES

('пользователи: просмотр', 'users:show', 1, 'superuser'),
('пользователи: редактирование', 'users:edit', 1, 'superuser'),
('пользователи: смена статуса', 'users:activity', 1, 'superuser'),
('идеи: просмотр', 'ideas:show', 1, 'superuser'),
('идеи: редактирование', 'ideas:edit', 1, 'superuser'),

('пользователи: редактирование', 'users:edit', 0, 'administrator'),
('пользователи: смена статуса', 'users:activity', 1, 'administrator'),
('пользователи: просмотр', 'users:show', 1, 'administrator'),
('идеи: редактирование', 'ideas:edit', 1, 'administrator'),
('идеи: просмотр', 'ideas:show', 1, 'administrator'),

('пользователи: редактирование', 'users:edit', 0, 'editor'),
('пользователи: смена статуса', 'users:activity', 0, 'administrator'),
('пользователи: просмотр', 'users:show', 0, 'editor'),
('идеи: редактирование', 'ideas:edit', 0, 'editor'),
('идеи: просмотр', 'ideas:show', 1, 'editor'),

('пользователи: редактирование', 'users:edit', 0, 'user'),
('пользователи: смена статуса', 'users:activity', 0, 'administrator'),
('пользователи: просмотр', 'users:show', 0, 'user'),
('идеи: редактирование', 'ideas:edit', 0, 'user'),
('идеи: просмотр', 'ideas:show', 1, 'user');


CREATE TABLE `ideas` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `title` varchar(256) NOT NULL,
  `content` text NOT NULL,
  `creator` int(10) NOT NULL,
  `lasteditor` int(10) NOT NULL,
  `status` enum('new', 'processing', 'complete', 'archive', 'deleted'),
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARSET = utf8;

INSERT INTO `ideas` (`title`, `creator`, `lasteditor`, `status`, `created`, `updated`, `content`) VALUES
('Idea #1', 1, 1, 'new', NOW(), NOW(), 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Soluta ducimus cum quam iusto, aspernatur eveniet? Optio quaerat blanditiis temporibus, at similique reprehenderit laboriosam cumque explicabo possimus totam veritatis, quod nostrum!'),
('Idea #2', 2, 2, 'new', NOW(), NOW(), 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Hic deserunt earum necessitatibus esse suscipit tempore soluta tenetur quae, cumque commodi reiciendis autem nobis, inventore architecto ab error vero, est iusto.'),
('Idea #3', 3, 3, 'processing', NOW(), NOW(), 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid nemo ipsam amet vero nulla perferendis sint, perspiciatis eum nobis inventore laborum repudiandae, tenetur esse laudantium reprehenderit, fuga beatae minima autem.'),
('Idea #4', 4, 4, 'complete', NOW(), NOW(), 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptates recusandae assumenda odio cum soluta id commodi nemo fugiat minima? Delectus quos excepturi reiciendis distinctio quam! Vel, veniam, repellendus. Sint, debitis.'),
('Idea #5', 5, 5, 'complete', NOW(), NOW(), 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Temporibus, quaerat obcaecati voluptatem, tempora neque dignissimos quas voluptatibus voluptate recusandae aut, assumenda provident, possimus nulla quis dolorum perferendis. Dignissimos, ex, blanditiis.'),
('Idea #4', 4, 4, 'archive', NOW(), NOW(), 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptates recusandae assumenda odio cum soluta id commodi nemo fugiat minima? Delectus quos excepturi reiciendis distinctio quam! Vel, veniam, repellendus. Sint, debitis.'),
('Idea #5', 5, 5, 'deleted', NOW(), NOW(), 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Temporibus, quaerat obcaecati voluptatem, tempora neque dignissimos quas voluptatibus voluptate recusandae aut, assumenda provident, possimus nulla quis dolorum perferendis. Dignissimos, ex, blanditiis.');

CREATE TABLE `comments` (
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `idea_id` int(12) NOT NULL,
  `creator` int(10) NOT NULL,
  `content` text NOT NULL,
  `created` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE = InnoDB CHARSET = utf8;

INSERT INTO `comments` (`idea_id`, `creator`, `content`, `created`) VALUES
(1, 1, 'Lorem ipsum dolore sit amet... ', '2016-06-02 20:50:00'),
(1, 2, 'Lorem ipsum dolore sit amet... ', '2016-06-02 20:51:00'),
(1, 3, 'Lorem ipsum dolore sit amet... ', '2016-06-02 20:52:00'),
(1, 4, 'Lorem ipsum dolore sit amet... ', '2016-06-02 20:53:00'),
(1, 5, 'Lorem ipsum dolore sit amet... ', '2016-06-02 20:54:00'),
(2, 1, 'Lorem ipsum dolore sit amet... ', '2016-06-02 20:55:00'),
(2, 2, 'Lorem ipsum dolore sit amet... ', '2016-06-02 20:56:00'),
(3, 3, 'Lorem ipsum dolore sit amet... ', '2016-06-02 20:57:00'),
(3, 4, 'Lorem ipsum dolore sit amet... ', '2016-06-02 20:58:00'),
(3, 5, 'Lorem ipsum dolore sit amet... ', '2016-06-02 20:58:00');