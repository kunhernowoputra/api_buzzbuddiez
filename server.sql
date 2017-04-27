-- MySQL dump 10.13  Distrib 5.7.13, for Linux (x86_64)
--
-- Host: localhost    Database: api_backend_lumen
-- ------------------------------------------------------
-- Server version	5.7.13-0ubuntu0.16.04.2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expressions`
--

DROP TABLE IF EXISTS `expressions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expressions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expressions`
--

LOCK TABLES `expressions` WRITE;
/*!40000 ALTER TABLE `expressions` DISABLE KEYS */;
/*!40000 ALTER TABLE `expressions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groups`
--

DROP TABLE IF EXISTS `groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `privacy` tinyint(4) NOT NULL,
  `interest_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groups`
--

LOCK TABLES `groups` WRITE;
/*!40000 ALTER TABLE `groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `interests`
--

DROP TABLE IF EXISTS `interests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `interests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `colour` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `photo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `interests`
--

LOCK TABLES `interests` WRITE;
/*!40000 ALTER TABLE `interests` DISABLE KEYS */;
INSERT INTO `interests` VALUES (1,'Animal','','i_animal.jpg'),(2,'Culture','6D7B8A','i_art.jpg'),(3,'Health','','i_health.jpg'),(4,'Fitness & Health','EF7D16','i_fitness.jpg'),(5,'Nutrition','','i_nutrition.jpg'),(6,'Beauty','27AAE1','i_beauty.jpg'),(7,'Sport','6D7B8A','i_sport.jpg'),(8,'Travel','F26522','i_travel.jpg'),(9,'Technology','F24D34','i_technology.jpg'),(10,'Fashion','F26522','i_fashion.jpg'),(11,'Culinary','FC9A00','i_food_beverage.jpg'),(12,'Home','','i_home.jpg'),(13,'Music','FC9A00','i_music.jpg'),(14,'Movie','20313F','i_movie.jpg'),(15,'Book','00C973','i_book.jpg'),(16,'Game','EF7D16','i_game.jpg'),(17,'Celebrity','F24D34','i_celebrities.jpg'),(18,'Humour','13C7B0','i_humour.jpg'),(19,'TV Shows','','i_tv_shows.jpg'),(20,'News','FA92AC','i_news.jpg'),(21,'Science','','i_science.jpg'),(22,'Enterpreneur','','i_entrepreneurship.jpg'),(23,'Photography','13C7B0','i_photography.jpg'),(24,'Art & Design','27AAE1','i_design.jpg'),(25,'Architecture','','i_architecture.jpg'),(26,'Camera','','i_camera.jpg'),(27,'Gadget','FA92AC','i_cellphone_tablet.jpg'),(28,'Computer','','i_computer_laptop.jpg'),(29,'Home Appliance','','i_home_appliances.jpg'),(30,'Men fashion','','i_men_fashion.jpg'),(31,'Woman fashion','','i_women_fashion.jpg'),(32,'Accessories','','i_accessories.jpg'),(33,'Watches','','i_watches.jpg'),(34,'Beverage','','i_beverage.jpg'),(35,'Dessert','','i_dessert.jpg'),(36,'Recipe','','i_recipe.jpg'),(37,'Restaurant','','i_restaurant.jpg'),(38,'Misc','27AAE1','i_misc.jpg'),(39,'Automotive','00C973','i_automotive.jpg'),(40,'Business','EF7D16','i_business.jpg'),(41,'Quotes','','i_quote.jpg'),(42,'Comic','EF7D16','i_comic.jpg'),(43,'Toy','9D5EB4','i_toy.jpg'),(44,'Cosplay','20313F','i_cosplay.jpg'),(45,'Love','FC9A00','i_love.jpg'),(46,'Education','9D5EB4','i_education.jpg'),(47,'Pet','FA92AC','i_pet.jpg'),(48,'Anime','20313F','i_anime.jpg'),(49,'Commerce','00C973','i_commerce.jpg'),(50,'Quiz','EF7D16','i_quiz.jpg'),(51,'Politic','F6921E','i_politic.png'),(52,'Sheila On 7','E67365','i_so7.png');
/*!40000 ALTER TABLE `interests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `links`
--

DROP TABLE IF EXISTS `links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `links` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `links`
--

LOCK TABLES `links` WRITE;
/*!40000 ALTER TABLE `links` DISABLE KEYS */;
/*!40000 ALTER TABLE `links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `locations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `lat` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `long` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` VALUES (1,1,'23242342342332423','23242342342332423','Medan','2016-09-14 04:53:42','2016-09-14 04:53:42');
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES ('2016_08_31_063053_create_table_posts',1),('2016_08_31_063706_create_table_users',1),('2016_08_31_064804_create_table_location',1),('2016_08_31_064955_create_table_group',1),('2016_08_31_065442_create_table_interest',1),('2016_08_31_065846_create_table_video',1),('2016_08_31_070217_create_table_expression',1),('2016_08_31_070454_create_table_links',1),('2016_08_31_070620_create_table_comments',1),('2016_08_31_070755_create_table_stickers',1),('2016_08_31_070926_create_table_settings',1),('2016_08_31_071026_create_table_photos',1),('2016_09_06_040138_create_table_pollings',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `photos`
--

DROP TABLE IF EXISTS `photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `photos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `original` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `photos`
--

LOCK TABLES `photos` WRITE;
/*!40000 ALTER TABLE `photos` DISABLE KEYS */;
INSERT INTO `photos` VALUES (1,1,'Screenshot_from_2016-08-29_17-15-23.png','Screenshot_from_2016-08-29_17-15-23.png',''),(2,1,'Screenshot_from_2016-08-29_17-01-40.png','Screenshot_from_2016-08-29_17-01-40.png',''),(3,1,'Screenshot_from_2016-08-29_16-59-38.png','Screenshot_from_2016-08-29_16-59-38.png',''),(4,0,'https://en.opensuse.org/images/0/0b/Icon-user.png','https://en.opensuse.org/images/0/0b/Icon-user.png','Aliquid iusto fugiat omnis quidem qui eum quae perferendis. Distinctio et impedit possimus reprehenderit. Et deleniti sit voluptatem quia aut optio. Numquam eligendi fugiat inventore voluptatibus.'),(5,0,'','','Iure vel officia non amet recusandae. Libero quos placeat aut illum soluta deserunt. Doloremque natus fugiat nostrum voluptate omnis eligendi rerum. Unde culpa quia architecto.');
/*!40000 ALTER TABLE `photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `polling_answers`
--

DROP TABLE IF EXISTS `polling_answers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `polling_answers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `polling_item_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `polling_answers`
--

LOCK TABLES `polling_answers` WRITE;
/*!40000 ALTER TABLE `polling_answers` DISABLE KEYS */;
/*!40000 ALTER TABLE `polling_answers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `polling_items`
--

DROP TABLE IF EXISTS `polling_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `polling_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `polling_id` int(11) NOT NULL,
  `answer` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `polling_items`
--

LOCK TABLES `polling_items` WRITE;
/*!40000 ALTER TABLE `polling_items` DISABLE KEYS */;
INSERT INTO `polling_items` VALUES (1,1,'a'),(2,1,'b');
/*!40000 ALTER TABLE `polling_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pollings`
--

DROP TABLE IF EXISTS `pollings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pollings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `question` text COLLATE utf8_unicode_ci NOT NULL,
  `end` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pollings`
--

LOCK TABLES `pollings` WRITE;
/*!40000 ALTER TABLE `pollings` DISABLE KEYS */;
INSERT INTO `pollings` VALUES (1,2,'tes','2000-10-10');
/*!40000 ALTER TABLE `pollings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_expressions`
--

DROP TABLE IF EXISTS `post_expressions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_expressions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `expression_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_expressions`
--

LOCK TABLES `post_expressions` WRITE;
/*!40000 ALTER TABLE `post_expressions` DISABLE KEYS */;
/*!40000 ALTER TABLE `post_expressions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_stickers`
--

DROP TABLE IF EXISTS `post_stickers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_stickers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `sticker_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_stickers`
--

LOCK TABLES `post_stickers` WRITE;
/*!40000 ALTER TABLE `post_stickers` DISABLE KEYS */;
INSERT INTO `post_stickers` VALUES (1,1,11),(2,1,12),(3,1,16);
/*!40000 ALTER TABLE `post_stickers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `interest_id` int(11) NOT NULL,
  `link_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `setting_id` int(11) NOT NULL,
  `post_type` int(11) NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,1,10,0,0,0,2,'Bisa gk ya','2016-09-14 04:53:41','2016-09-14 04:53:41'),(2,1,2,0,0,0,4,'','2016-09-14 05:20:32','2016-09-14 05:20:32');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stickers`
--

DROP TABLE IF EXISTS `stickers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `stickers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=92 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stickers`
--

LOCK TABLES `stickers` WRITE;
/*!40000 ALTER TABLE `stickers` DISABLE KEYS */;
INSERT INTO `stickers` VALUES (1,'jempolan-blushing.png','jempolan'),(2,'jempolan-chill.png','jempolan'),(3,'jempolan-creepy.png','jempolan'),(4,'jempolan-cry.png','jempolan'),(5,'jempolan-evil.png','jempolan'),(6,'jempolan-face.png','jempolan'),(7,'jempolan-kiss.png','jempolan'),(8,'jempolan-laugh.png','jempolan'),(9,'jempolan-love.png','jempolan'),(10,'jempolan-sad.png','jempolan'),(11,'jempolan-smile.png','jempolan'),(12,'jempolan-stunned.png','jempolan'),(13,'jempolan-weks.png','jempolan'),(14,'angry.png','buzzbuddies'),(15,'cool.png','buzzbuddies'),(16,'crazy.png','buzzbuddies'),(17,'damn.png','buzzbuddies'),(18,'depressed.png','buzzbuddies'),(19,'evil-plan.png','buzzbuddies'),(20,'fantasy-dream.png','buzzbuddies'),(21,'geek.png','buzzbuddies'),(22,'gemes.png','buzzbuddies'),(23,'glad.png','buzzbuddies'),(24,'gotcha.png','buzzbuddies'),(25,'haha.png','buzzbuddies'),(26,'heeeeey.png','buzzbuddies'),(27,'hey.png','buzzbuddies'),(28,'hohoho.png','buzzbuddies'),(29,'huh-where.png','buzzbuddies'),(30,'idiotic-smile.png','buzzbuddies'),(31,'indescent-love.png','buzzbuddies'),(32,'kiss.png','buzzbuddies'),(33,'lol.png','buzzbuddies'),(34,'love.png','buzzbuddies'),(35,'mmmm.png','buzzbuddies'),(36,'muahaha.png','buzzbuddies'),(37,'offended.png','buzzbuddies'),(38,'omg.png','buzzbuddies'),(39,'oopsy.png','buzzbuddies'),(40,'ouch.png','buzzbuddies'),(41,'ow-shh.png','buzzbuddies'),(42,'sarcastic-sadness.png','buzzbuddies'),(43,'scared.png','buzzbuddies'),(44,'sincere-sadness.png','buzzbuddies'),(45,'stayaway.png','buzzbuddies'),(46,'stupid-idea.png','buzzbuddies'),(47,'this-is-sparta.png','buzzbuddies'),(48,'very-evil-plan.png','buzzbuddies'),(49,'waw-dude.png','buzzbuddies'),(50,'wek.png','buzzbuddies'),(51,'whopsy.png','buzzbuddies'),(52,'wink.png','buzzbuddies'),(53,'wtf.png','buzzbuddies'),(54,'affraid.png','monkey'),(55,'angry.png','monkey'),(56,'disappointed.png','monkey'),(57,'falling-love.png','monkey'),(58,'furious.png','monkey'),(59,'happy.png','monkey'),(60,'huge.png','monkey'),(61,'impressed.png','monkey'),(62,'shock.png','monkey'),(63,'sleep.png','monkey'),(64,'sleepy.png','monkey'),(65,'thinking.png','monkey'),(66,'aww.png','buzzy'),(67,'ciaat.png','buzzy'),(68,'fiuh.png','buzzy'),(69,'hehe.png','buzzy'),(70,'hihi.png','buzzy'),(71,'huft.png','buzzy'),(72,'pffft.png','buzzy'),(73,'tickle.png','buzzy'),(74,'waw.png','buzzy'),(75,'weekz.png','buzzy'),(76,'wuzz.png','buzzy'),(77,'yummy.png','buzzy'),(78,'blushing.png','cowgirl'),(79,'butterfly.png','cowgirl'),(80,'chill.png','cowgirl'),(81,'confused.png','cowgirl'),(82,'cry.png','cowgirl'),(83,'fallen.png','cowgirl'),(84,'furious.png','cowgirl'),(85,'hmm.png','cowgirl'),(86,'lonely.png','cowgirl'),(87,'love.png','cowgirl'),(88,'love_2.png','cowgirl'),(89,'neglected.png','cowgirl'),(90,'sad.png','cowgirl'),(91,'sleep.png','cowgirl');
/*!40000 ALTER TABLE `stickers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_profiles`
--

DROP TABLE IF EXISTS `user_profiles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_profiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `photo_id` int(11) NOT NULL,
  `fullname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `birthday` date NOT NULL,
  `about` text COLLATE utf8_unicode_ci NOT NULL,
  `gender` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_profiles`
--

LOCK TABLES `user_profiles` WRITE;
/*!40000 ALTER TABLE `user_profiles` DISABLE KEYS */;
INSERT INTO `user_profiles` VALUES (1,1,4,'Mrs. Ernestine Ankunding','2000-08-30','Ipsa corporis iste odio deserunt laborum. Vel facere iusto sed. Omnis cum perspiciatis qui qui minima enim voluptatum. Voluptatibus ad qui amet molestiae non aliquam eos.','Laki Laki','Maryland','2016-09-14 04:54:05','2016-09-14 04:54:05'),(2,2,5,'Stephon Ledner','2014-12-17','Aut est vel debitis eligendi animi consequatur. Laudantium ipsam voluptatum facere quas ea. Officia sed enim impedit sapiente excepturi doloribus dolores. Quidem voluptatem vel rem reiciendis.','Laki Laki','California','2016-09-14 06:36:48','2016-09-14 06:36:48');
/*!40000 ALTER TABLE `user_profiles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_tags`
--

DROP TABLE IF EXISTS `user_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_tags` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_tags`
--

LOCK TABLES `user_tags` WRITE;
/*!40000 ALTER TABLE `user_tags` DISABLE KEYS */;
INSERT INTO `user_tags` VALUES (1,1,12),(2,1,10);
/*!40000 ALTER TABLE `user_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remember_token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'tgreenfelder@beatty.com','tracey47','','123456','2016-09-14 04:54:05','2016-09-14 04:54:05'),(2,'mckenzie.gunnar@wolff.info','durgan.osbaldo','','','2016-09-14 06:36:48','2016-09-14 06:36:48');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `videos`
--

DROP TABLE IF EXISTS `videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `videos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(11) NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `videos`
--

LOCK TABLES `videos` WRITE;
/*!40000 ALTER TABLE `videos` DISABLE KEYS */;
/*!40000 ALTER TABLE `videos` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-09-15 13:11:58
