
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for m_access_log
-- ----------------------------
DROP TABLE IF EXISTS `m_access_log`;
CREATE TABLE `m_access_log`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `app_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '应用',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `user_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '用户类型同设定',
  `route` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '路由',
  `ip` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'ip',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '时间',
  `run_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '运行时间(毫秒)',
  `all_params` json NULL COMMENT '所有参数',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 472 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '访问记录' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for m_admin_group
-- ----------------------------
DROP TABLE IF EXISTS `m_admin_group`;
CREATE TABLE `m_admin_group`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分组名',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `show_sort` smallint(6) NOT NULL DEFAULT 100 COMMENT '排序',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态',
  `add_time` datetime(0) NOT NULL COMMENT '添加时间',
  `add_admin_id` int(11) NULL DEFAULT 0 COMMENT '添加人',
  `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `show_sort`(`show_sort`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '管理员分组' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for m_admin_log
-- ----------------------------
DROP TABLE IF EXISTS `m_admin_log`;
CREATE TABLE `m_admin_log`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员',
  `module` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '模块',
  `contoller` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '控制器',
  `action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '行为',
  `ip` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'ip',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '操作时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 457 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '管理员操作记录' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for m_admin_login_log
-- ----------------------------
DROP TABLE IF EXISTS `m_admin_login_log`;
CREATE TABLE `m_admin_login_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '名称',
  `type` tinyint(2) NOT NULL DEFAULT 0 COMMENT '登录类型',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录时间',
  `ip` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'ip',
  `device_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设备描述',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '管理员登录日志' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for m_admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `m_admin_menu`;
CREATE TABLE `m_admin_menu`  (
  `id` mediumint(6) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单名',
  `controller` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '对应controller',
  `c_action` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '对应action',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型=',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态',
  `icon` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图标',
  `parent_id` mediumint(6) NOT NULL DEFAULT 0 COMMENT '上级菜单',
  `m_level` tinyint(1) NOT NULL DEFAULT 1 COMMENT '等级',
  `add_admin_id` int(11) NOT NULL DEFAULT 0 COMMENT '添加人',
  `add_time` datetime(0) NULL COMMENT '添加时间',
  `show_sort` mediumint(6) NOT NULL DEFAULT 100 COMMENT '排序',
  `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `parentId`(`parent_id`) USING BTREE,
  INDEX `showSort`(`show_sort`) USING BTREE,
  INDEX `level`(`m_level`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '管理员权限菜单' ROW_FORMAT = Compact;


-- ----------------------------
-- Table structure for m_administrator
-- ----------------------------
DROP TABLE IF EXISTS `m_administrator`;
CREATE TABLE `m_administrator`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `login_username` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '登录用户名',
  `avatar` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `realname` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '真实姓名',
  `nickname` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '姓名',
  `mobile` char(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `email` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `qq` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'QQ',
  `wechat` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微信',
  `company` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '公司',
  `role_id` smallint(6) NOT NULL COMMENT '角色',
  `group_id` smallint(6) NOT NULL DEFAULT 0 COMMENT '分组',
  `area_id` int(10) NOT NULL DEFAULT 0 COMMENT '城市',
  `login_password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
  `token` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'token',
  `add_admin_id` int(11) NOT NULL DEFAULT 0 COMMENT '添加人',
  `add_time` datetime(0) NULL COMMENT '添加时间',
  `show_sort` mediumint(6) NOT NULL DEFAULT 100 COMMENT '排序',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态',
  `last_login_time` datetime(0) NULL COMMENT '最后登陆时间',
  `last_login_ip` char(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '最后登陆IP',
  `online` tinyint(1) NOT NULL DEFAULT 1 COMMENT '在线状态',
  `is_admin` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否为超级管理员',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `login_username`(`login_username`) USING BTREE,
  INDEX `loginPassword`(`login_password`) USING BTREE,
  INDEX `showSort`(`show_sort`) USING BTREE,
  INDEX `mobile`(`mobile`) USING BTREE,
  INDEX `role_id`(`role_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '管理员' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for m_app
-- ----------------------------
DROP TABLE IF EXISTS `m_app`;
CREATE TABLE `m_app`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '应用名称',
  `status` tinyint(2) NOT NULL DEFAULT 0 COMMENT '应用状态',
  `type` tinyint(2) NOT NULL DEFAULT 0 COMMENT '应用类型',
  `img_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '应用配图',
  `liaison` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '联络人',
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '电话',
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `qq` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'QQ',
  `weixin` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微信',
  `weibo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微博',
  `province_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '省',
  `city_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '市',
  `area_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '区',
  `address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '地址',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '详细介绍',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '应用' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for m_area
-- ----------------------------
DROP TABLE IF EXISTS `m_area`;
CREATE TABLE `m_area`  (
  `id` mediumint(6) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '地区名称',
  `status` tinyint(2) NOT NULL DEFAULT 0 COMMENT '应用状态',
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '地区类型',
  `parent_id` mediumint(6) NOT NULL DEFAULT 0 COMMENT '上级区域',
  `show_sort` mediumint(6) NOT NULL DEFAULT 100 COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `type`(`type`) USING BTREE,
  INDEX `parentId`(`parent_id`) USING BTREE,
  INDEX `showSort`(`show_sort`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 810001 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '全国区域表' ROW_FORMAT = Dynamic;


-- ----------------------------
-- Table structure for m_log
-- ----------------------------
DROP TABLE IF EXISTS `m_log`;
CREATE TABLE `m_log`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `level` int(11) NULL DEFAULT NULL COMMENT '等级',
  `category` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL COMMENT '分类标记',
  `log_time` double NULL DEFAULT NULL COMMENT '记录时间',
  `prefix` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '前缀',
  `message` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL COMMENT '内容',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_log_level`(`level`) USING BTREE,
  INDEX `idx_log_category`(`category`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 999 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci COMMENT = '系统日志' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for m_queue
-- ----------------------------
DROP TABLE IF EXISTS `m_queue`;
CREATE TABLE `m_queue`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `channel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `job` blob NOT NULL,
  `pushed_at` int(11) NOT NULL,
  `ttr` int(11) NOT NULL,
  `delay` int(11) NOT NULL DEFAULT 0,
  `priority` int(11) UNSIGNED NOT NULL DEFAULT 1024,
  `reserved_at` int(11) NULL DEFAULT NULL,
  `attempt` int(11) NULL DEFAULT NULL,
  `done_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `channel`(`channel`(191)) USING BTREE,
  INDEX `reserved_at`(`reserved_at`) USING BTREE,
  INDEX `priority`(`priority`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 101 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for m_site
-- ----------------------------
DROP TABLE IF EXISTS `m_site`;
CREATE TABLE `m_site`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '网站名称',
  `introduce` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '网站简介',
  `seo_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `seo_keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO关键字',
  `seo_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `telphone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '联系电话',
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `qq` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'QQ',
  `email` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `img_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'logo',
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '封面',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '详细介绍',
  `about_us` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '关于我们',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '网站设置' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for m_sms_email
-- ----------------------------
DROP TABLE IF EXISTS `m_sms_email`;
CREATE TABLE `m_sms_email`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '消息名称',
  `status` tinyint(2) NOT NULL DEFAULT 0 COMMENT '状态',
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '类型',
  `app_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '应用',
  `object_id` int(11) NOT NULL DEFAULT 0 COMMENT '消息对象ID',
  `object_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '消息对象类型',
  `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '接收用户',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '邮箱',
  `other_emails` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '抄送邮箱',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮件标题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '发送内容',
  `params_json` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '附加参数',
  `add_time` int(10) NOT NULL DEFAULT 0 COMMENT '添加时间',
  `send_time` int(10) NOT NULL DEFAULT 0 COMMENT '发送时间',
  `send_num` tinyint(3) NOT NULL DEFAULT 0 COMMENT '发送次数',
  `send_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '发送类型',
  `sms_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '邮件渠道',
  `template` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '发送模板',
  `feedback` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '发送反馈',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮件备注',
  `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `email`(`email`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '邮件记录' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for m_sms_mobile
-- ----------------------------
DROP TABLE IF EXISTS `m_sms_mobile`;
CREATE TABLE `m_sms_mobile`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '短信名称',
  `app_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '应用',
  `status` tinyint(2) NOT NULL DEFAULT 0 COMMENT '状态',
  `object_id` int(11) NOT NULL DEFAULT 0 COMMENT '短信对象ID',
  `object_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '短信对象类型',
  `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '接收用户',
  `area_code` int(10) NOT NULL DEFAULT 0 COMMENT '手机区号',
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '手机号',
  `other_mobiles` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '其他接收手机号',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '发送内容',
  `params_json` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '附加参数',
  `send_time` int(10) NOT NULL DEFAULT 0 COMMENT '发送时间',
  `send_num` tinyint(3) NOT NULL DEFAULT 0 COMMENT '发送次数',
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '类型}',
  `send_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '发送类型',
  `sms_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '短信渠道',
  `template` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '发送模板}',
  `feedback` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '发送反馈',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '短信备注',
  `add_time` int(10) NOT NULL DEFAULT 0 COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `mobile`(`mobile`) USING BTREE,
  INDEX `status`(`status`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '短信记录' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for m_source_channel
-- ----------------------------
DROP TABLE IF EXISTS `m_source_channel`;
CREATE TABLE `m_source_channel`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '渠道ID',
  `app_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '应用',
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '渠道名称',
  `channel_code` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '渠道唯一码',
  `status` tinyint(2) NOT NULL DEFAULT 0 COMMENT '渠道状态',
  `type` tinyint(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '渠道分类',
  `img_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '渠道配图',
  `province_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '省',
  `city_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '市',
  `area_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '区',
  `address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '地址',
  `liaison` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '联络人',
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '电话',
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `qq` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'QQ',
  `weixin` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微信',
  `weibo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微博',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '关键字',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述',
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '来源渠道' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for m_system_config
-- ----------------------------
DROP TABLE IF EXISTS `m_system_config`;
CREATE TABLE `m_system_config`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '配置名称',
  `c_val` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '配置值',
  `desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '描述',
  `add_time` int(11) NOT NULL DEFAULT 0 COMMENT '添加时间',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `un_name`(`name`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '系统配置文件' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for m_test_use_table
-- ----------------------------
DROP TABLE IF EXISTS `m_test_use_table`;
CREATE TABLE `m_test_use_table`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'name',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'status',
  `add_time` int(10) NOT NULL DEFAULT 0 COMMENT 'add_time',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT 'content',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '数据库性能测试表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for m_testjob
-- ----------------------------
DROP TABLE IF EXISTS `m_testjob`;
CREATE TABLE `m_testjob`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `p1` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '参数1',
  `p2` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '参数2',
  `addtime` int(11) NOT NULL DEFAULT 0 COMMENT '添加时间',
  `uptime` int(11) NOT NULL DEFAULT 0 COMMENT '修改次数',
  `extime` int(11) NOT NULL DEFAULT 0 COMMENT '执行时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 101 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for m_user
-- ----------------------------
DROP TABLE IF EXISTS `m_user`;
CREATE TABLE `m_user`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `app_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '应用',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '昵称',
  `area_code` int(10) NOT NULL DEFAULT 0 COMMENT '手机区号',
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名'
  `status` tinyint(2) NOT NULL DEFAULT 0 COMMENT '状态',
  `type` tinyint(2) NOT NULL DEFAULT 0 COMMENT '用户类型',
  `level` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '等级',
  `realname` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '真实姓名',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '注册时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 86 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for m_user_login_bind
-- ----------------------------
DROP TABLE IF EXISTS `m_user_login_bind`;
CREATE TABLE `m_user_login_bind`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户登录绑定' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for m_user_login_bind_third
-- ----------------------------
DROP TABLE IF EXISTS `m_user_login_bind_third`;
CREATE TABLE `m_user_login_bind_third`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `bind_num` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '登录平台号',
  `bind_nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `bind_avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `bind_sex` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '性别',
  `bind_birthday` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '生日',
  `bind_district` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '城市',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改时间}',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `bind_unique`(`bind_key`, `type`, `app_id`) USING BTREE COMMENT '第三方绑定唯一索引',
  INDEX `user_id`(`user_id`) USING BTREE COMMENT '用户索引'
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户第三方登录绑定' ROW_FORMAT = Dynamic;


-- ----------------------------
-- Table structure for m_user_login_log
-- ----------------------------
DROP TABLE IF EXISTS `m_user_login_log`;
CREATE TABLE `m_user_login_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `app_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '应用=',
  `login_type` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录类型',
  `system` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设备系统',
  `model` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设备型号',
  `district` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '地区',
  `device_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设备描述',
  `ip` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'ip',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间',
  PRIMARY KEY (`id`) USING BTREE,
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户登录记录' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
