--
-- Table structure for table `#__tuf_metadata`
--

CREATE TABLE IF NOT EXISTS `#__tuf_metadata` (
  `id` int NOT NULL AUTO_INCREMENT,
  `extension_id` int DEFAULT 0,
  `root` text DEFAULT NULL,
  `target` text DEFAULT NULL,
  `snapshot` text DEFAULT NULL,
  `timestamp` text DEFAULT NULL,
  `mirrors` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 DEFAULT COLLATE=utf8mb4_unicode_ci COMMENT='Secure TUF Updates';

-- --------------------------------------------------------
INSERT INTO `#__tuf_metadata` (`extension_id`, `root`)
SELECT `extension_id`, '{"_type":"root","spec_version":"1.0","version":3,"expires":"2024-12-30T13:31:20Z","keys":{"1e456d8b1aebbf1812f8181b8ffb30100864210ff203eec1a32faf72cc5921e8":{"keytype":"ed25519","scheme":"ed25519","keyid_hash_algorithms":["sha256","sha512"],"keyval":{"public":"cf4408fde3f3db32e1fd26dc4d3ae0eb00d0461aa22be34ccb8f3b863b69e56d"}},"6e6ea0f74918cff8deb1dfd5bfa5471c71a210106604081df0696cb6bc793bfc":{"keytype":"ed25519","scheme":"ed25519","keyid_hash_algorithms":["sha256","sha512"],"keyval":{"public":"c8e4c29b16f04419a54b72628de0e3e98f554a744d276dc1bb6a5410ac712c33"}},"788c596eb4b3d51f00a5bac53c904e6830d9a75d47fd37fab6bce13811268e5a":{"keytype":"ed25519","scheme":"ed25519","keyid_hash_algorithms":["sha256","sha512"],"keyval":{"public":"9fa0fc53c0466a73c6e585ea13e32b6c61bb807259f15f60ec458d944d6d69ea"}},"baf247cd493b5a0190304b26cf099fbaf6c6f4dd1c5a749b4265ecd8a7ae2ced":{"keytype":"ed25519","scheme":"ed25519","keyid_hash_algorithms":["sha256","sha512"],"keyval":{"public":"99f06efa082f1be2a9cacc8803e2cbe814a37e5f25b7289f08c7989d9616e6d4"}},"d363dcdfdbab98bc60e367e83c0b338e89c27bbdcd20517b1315d24b41c254dd":{"keytype":"ed25519","scheme":"ed25519","keyid_hash_algorithms":["sha256","sha512"],"keyval":{"public":"03c41b4aa5eb4e759d20b2e6e72084cdacd038dce3a9add8f8e450d7060f88ab"}},"df88d5d857df7ab7018a72ca5c033b3f419b8156cecf9f8a247be0b5d6d9d30e":{"keytype":"ed25519","scheme":"ed25519","keyid_hash_algorithms":["sha256","sha512"],"keyval":{"public":"e8d1faa248040a41a668fae3d2a5e9c56c2178afd9b2b8d09641bda8b4e8a7ee"}}},"roles":{"root":{"keyids":["df88d5d857df7ab7018a72ca5c033b3f419b8156cecf9f8a247be0b5d6d9d30e"],"threshold":1},"snapshot":{"keyids":["baf247cd493b5a0190304b26cf099fbaf6c6f4dd1c5a749b4265ecd8a7ae2ced","ID"],"threshold":1},"targets":{"keyids":["d363dcdfdbab98bc60e367e83c0b338e89c27bbdcd20517b1315d24b41c254dd","1e456d8b1aebbf1812f8181b8ffb30100864210ff203eec1a32faf72cc5921e8"],"threshold":2},"timestamp":{"keyids":["6e6ea0f74918cff8deb1dfd5bfa5471c71a210106604081df0696cb6bc793bfc","788c596eb4b3d51f00a5bac53c904e6830d9a75d47fd37fab6bce13811268e5a"],"threshold":1}},"consistent_snapshot":true},"signatures":[{"keyid":"df88d5d857df7ab7018a72ca5c033b3f419b8156cecf9f8a247be0b5d6d9d30e","sig":"3a5582627333a6cc9a2cc0acc471623f5456d28d3ca45494d4286fd671f0dd1ee1a711e249859000ea856d6bd2bd3576811035c88772b9fb62185189ca1d5605"}]}' FROM `#__extensions` WHERE `type`='file' AND `element`='joomla';
