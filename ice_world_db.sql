
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for m_access_log
-- ----------------------------
DROP TABLE IF EXISTS `m_access_log`;
CREATE TABLE `m_access_log`  (
  `id` bigint(16) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `app_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '应用=+={\"name\":\"appRecord\",\"type\":\"db\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"functionName\":\"getAppRecord\",\"modelName\":\"AppModel\",\"modelNamespace\":\"use common\\\\services\\\\application\\\\AppModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
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
-- Table structure for m_admin_auth
-- ----------------------------
DROP TABLE IF EXISTS `m_admin_auth`;
CREATE TABLE `m_admin_auth`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '名称',
  `auth_flag` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'controller/action',
  `parent_id` int(11) NOT NULL DEFAULT 0 COMMENT '上级=+={\"name\":\"parentAdminAuthRecord\",\"type\":\"db\",\"functionName\":\"getParentAdminAuthRecord\",\"modelName\":\"AdminAuthModel\",\"modelNamespace\":\"\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `other_auth_url` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '其他权限',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型=+={\"name\":\"typePredefine\",\"type\":\"text\",\"list\":\"[\\\"1\\\"=>\\\"controller\\\",\\\"2\\\"=>\\\"action\\\"]\"}',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态=+={\"name\":\"statusPredefine\",\"type\":\"text\",\"list\":\"[\\\"1\\\"=>\\\"启用\\\",\\\"2\\\"=>\\\"冻结\\\"]\"}',
  `add_admin_id` int(11) NOT NULL DEFAULT 0 COMMENT '添加人=+={\"name\":\"addAdminRecord\",\"type\":\"db\",\"functionName\":\"getAddAdminRecord\",\"modelName\":\"AdministratorModel\",\"modelNamespace\":\"use common\\\\services\\\\administrator\\\\AdministratorModel;\",\"joinTableId\":\"id\",\"showName\":\"nickname\",\"selectFeild\":\"id,nickname\"}',
  `add_time` datetime(0) NULL COMMENT '添加时间',
  `show_sort` mediumint(6) NOT NULL DEFAULT 100 COMMENT '排序',
  `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `addAdminId`(`add_admin_id`) USING BTREE,
  INDEX `showSort`(`show_sort`) USING BTREE,
  INDEX `parent_id`(`parent_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 37 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '管理员权限' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for m_admin_group
-- ----------------------------
DROP TABLE IF EXISTS `m_admin_group`;
CREATE TABLE `m_admin_group`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '分组名',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `show_sort` smallint(6) NOT NULL DEFAULT 100 COMMENT '排序',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型=+={\"name\":\"typePredefine\",\"type\":\"text\",\"list\":\"[\\\"1\\\"=>\\\"普通\\\",\\\"2\\\"=>\\\"特殊\\\"]\"}',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态=+={\"name\":\"statusPredefine\",\"type\":\"text\",\"list\":\"[\\\"1\\\"=>\\\"启用\\\",\\\"2\\\"=>\\\"冻结\\\"]\"}',
  `add_time` datetime(0) NOT NULL COMMENT '添加时间',
  `add_admin_id` int(11) NULL DEFAULT 0 COMMENT '添加人=+={\"name\":\"addAdminRecord\",\"type\":\"db\",\"functionName\":\"getAddAdminRecord\",\"modelName\":\"AdministratorModel\",\"modelNamespace\":\"use common\\\\services\\\\administrator\\\\AdministratorModel;\",\"joinTableId\":\"id\",\"showName\":\"nickname\",\"selectFeild\":\"id,nickname\"}',
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
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '管理员=+={\"name\":\"adminRecord\",\"type\":\"db\",\"functionName\":\"getAdminRecord\",\"modelName\":\"AdministratorModel\",\"modelNamespace\":\"use common\\\\services\\\\admin\\\\AdministratorModel;\",\"joinTableId\":\"id\",\"showName\":\"nickname\",\"selectFeild\":\"id,nickname\"}',
  `module` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '模块',
  `contoller` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '控制器',
  `action` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '行为',
  `ip` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'ip',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '操作时间=+={\"name\":\"add_time\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1}',
  `all_params` json NULL COMMENT '所有参数=+={\"name\":\"all_params\",\"type\":\"more_text\",\"cuHide\":1,\"indexHide\":1}',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 457 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '管理员操作记录' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for m_admin_login_log
-- ----------------------------
DROP TABLE IF EXISTS `m_admin_login_log`;
CREATE TABLE `m_admin_login_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `admin_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '名称=+={\"name\":\"adminRecord\",\"type\":\"db\",\"functionName\":\"getAdminRecord\",\"modelName\":\"AdministratorModel\",\"modelNamespace\":\"use common\\\\services\\\\admin\\\\AdministratorModel;\",\"joinTableId\":\"id\",\"showName\":\"nickname\",\"selectFeild\":\"id,nickname\"}',
  `type` tinyint(2) NOT NULL DEFAULT 0 COMMENT '登录类型=+={\"name\":\"typePredefine\",\"type\":\"text\",\"list\":\"[\\\"0\\\"=>\\\"未设置\\\",\\\"1\\\"=>\\\"PC\\\",\\\"2\\\"=>\\\"客户端\\\"]\"}',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录时间=+={\"name\":\"add_time\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1}',
  `ip` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'ip',
  `device_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设备描述',
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态=+={\"name\":\"statusPredefine\",\"type\":\"text\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"list\":\"[\\\"1\\\"=>\\\"可见\\\"]\"}',
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
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型=+={\"name\":\"typePredefine\",\"type\":\"text\",\"list\":\"[\\\"1\\\"=>\\\"普通\\\",\\\"2\\\"=>\\\"特殊\\\"]\"}',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态=+={\"name\":\"statusPredefine\",\"type\":\"text\",\"list\":\"[\\\"1\\\"=>\\\"启用\\\",\\\"2\\\"=>\\\"冻结\\\"]\"}',
  `icon` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '图标',
  `parent_id` mediumint(6) NOT NULL DEFAULT 0 COMMENT '上级菜单=+={\"name\":\"parentMenuRecord\",\"type\":\"db\",\"functionName\":\"getParentMenuRecord\",\"modelName\":\"AdminMenuModel\",\"modelNamespace\":\"\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `m_level` tinyint(1) NOT NULL DEFAULT 1 COMMENT '等级=+={\"name\":\"levelPredefine\",\"type\":\"text\",\"list\":\"[\\\"1\\\"=>\\\"顶级菜单\\\",\\\"2\\\"=>\\\"二级菜单\\\"]\"}',
  `add_admin_id` int(11) NOT NULL DEFAULT 0 COMMENT '添加人=+={\"name\":\"addAdminRecord\",\"type\":\"db\",\"functionName\":\"getAddAdminRecord\",\"modelName\":\"AdministratorModel\",\"modelNamespace\":\"use common\\\\services\\\\administrator\\\\AdministratorModel;\",\"joinTableId\":\"id\",\"showName\":\"nickname\",\"selectFeild\":\"id,nickname\"}',
  `add_time` datetime(0) NULL COMMENT '添加时间',
  `show_sort` mediumint(6) NOT NULL DEFAULT 100 COMMENT '排序',
  `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `parentId`(`parent_id`) USING BTREE,
  INDEX `showSort`(`show_sort`) USING BTREE,
  INDEX `level`(`m_level`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '管理员权限菜单' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for m_admin_role
-- ----------------------------
DROP TABLE IF EXISTS `m_admin_role`;
CREATE TABLE `m_admin_role`  (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '角色名字',
  `auth_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '权限列表',
  `other_auth_list` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '附属权限列表',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型=+={\"name\":\"typePredefine\",\"type\":\"text\",\"list\":\"[\\\"1\\\"=>\\\"普通\\\",\\\"2\\\"=>\\\"特殊\\\"]\"}',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态=+={\"name\":\"statusPredefine\",\"type\":\"text\",\"list\":\"[\\\"1\\\"=>\\\"启用\\\",\\\"2\\\"=>\\\"冻结\\\"]\"}',
  `add_admin_id` int(11) NOT NULL DEFAULT 0 COMMENT '添加人=+={\"name\":\"addAdminRecord\",\"type\":\"db\",\"functionName\":\"getAddAdminRecord\",\"modelName\":\"AdministratorModel\",\"modelNamespace\":\"use common\\\\services\\\\administrator\\\\AdministratorModel;\",\"joinTableId\":\"id\",\"showName\":\"nickname\",\"selectFeild\":\"id,nickname\"}',
  `add_time` datetime(0) NULL COMMENT '添加时间',
  `show_sort` mediumint(6) NOT NULL DEFAULT 100 COMMENT '排序',
  `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `addAdminId`(`add_admin_id`) USING BTREE,
  INDEX `showSort`(`show_sort`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '管理员角色' ROW_FORMAT = Compact;

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
  `role_id` smallint(6) NOT NULL COMMENT '角色=+={\"name\":\"adminRoleRecord\",\"type\":\"db\",\"functionName\":\"getAdminRoleRecord\",\"modelName\":\"AdminRoleModel\",\"modelNamespace\":\"use common\\\\services\\\\adminrole\\\\AdminRoleModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `group_id` smallint(6) NOT NULL DEFAULT 0 COMMENT '分组=+={\"name\":\"adminGroupRecord\",\"type\":\"db\",\"functionName\":\"getAdminGroupRecord\",\"modelName\":\"AdminGroupModel\",\"modelNamespace\":\"use common\\\\services\\\\admingroup\\\\AdminGroupModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `area_id` int(10) NOT NULL DEFAULT 0 COMMENT '城市=+={\"name\":\"areaRecord\",\"type\":\"db\",\"functionName\":\"getAreaRecord\",\"modelName\":\"AreaModel\",\"modelNamespace\":\"use common\\\\services\\\\Area\\\\AreaModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `login_password` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '密码',
  `token` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'token',
  `add_admin_id` int(11) NOT NULL DEFAULT 0 COMMENT '添加人=+={\"name\":\"addAdminRecord\",\"type\":\"db\",\"functionName\":\"getAddAdminRecord\",\"modelName\":\"AdministratorModel\",\"modelNamespace\":\"\",\"joinTableId\":\"id\",\"showName\":\"nickname\",\"selectFeild\":\"id,nickname\"}',
  `add_time` datetime(0) NULL COMMENT '添加时间',
  `show_sort` mediumint(6) NOT NULL DEFAULT 100 COMMENT '排序',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型=+={\"name\":\"typePredefine\",\"type\":\"text\",\"list\":\"[\\\"1\\\"=>\\\"普通\\\",\\\"2\\\"=>\\\"特殊\\\"]\"}',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态=+={\"name\":\"statusPredefine\",\"type\":\"text\",\"list\":\"[\\\"1\\\"=>\\\"启用\\\",\\\"2\\\"=>\\\"冻结\\\"]\"}',
  `last_login_time` datetime(0) NULL COMMENT '最后登陆时间',
  `last_login_ip` char(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '最后登陆IP',
  `online` tinyint(1) NOT NULL DEFAULT 1 COMMENT '在线状态=+={\"name\":\"onlinePredefine\",\"type\":\"text\",\"list\":\"[\\\"1\\\"=>\\\"离线\\\",\\\"2\\\"=>\\\"在线\\\"]\"}',
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
  `status` tinyint(2) NOT NULL DEFAULT 0 COMMENT '应用状态=+={\"name\":\"statusPredefine\",\"type\":\"text\",\"list\":\"[\\\"1\\\"=>\\\"正常\\\",\\\"2\\\"=>\\\"冻结\\\"]\"}',
  `type` tinyint(2) NOT NULL DEFAULT 0 COMMENT '应用类型=+={\"name\":\"typePredefine\",\"type\":\"text\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"list\":\"[\\\"1\\\"=>\\\"系统应用\\\",\\\"2\\\"=>\\\"扩展应用\\\"]\"}',
  `img_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '应用配图=+={\"name\":\"img_url\",\"type\":\"upload_image\"}',
  `liaison` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '联络人=+={\"name\":\"liaison\",\"type\":\"val\"}',
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '电话=+={\"name\":\"phone\",\"type\":\"val\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号=+={\"name\":\"mobile\",\"type\":\"val\"}',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱=+={\"name\":\"email\",\"type\":\"val\"}',
  `qq` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'QQ=+={\"name\":\"qq\",\"type\":\"val\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `weixin` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微信=+={\"name\":\"weixin\",\"type\":\"val\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `weibo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微博=+={\"name\":\"weibo\",\"type\":\"val\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `province_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '省=+={\"name\":\"provinceRecord\",\"type\":\"db\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"functionName\":\"getProvinceRecord\",\"modelName\":\"AreaModel\",\"modelNamespace\":\"use common\\\\services\\\\Area\\\\AreaModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `city_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '市=+={\"name\":\"cityRecord\",\"type\":\"db\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"functionName\":\"getCityRecord\",\"modelName\":\"AreaModel\",\"modelNamespace\":\"use common\\\\services\\\\Area\\\\AreaModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `area_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '区=+={\"name\":\"areaRecord\",\"type\":\"db\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"functionName\":\"getAreaRecord\",\"modelName\":\"AreaModel\",\"modelNamespace\":\"use common\\\\services\\\\Area\\\\AreaModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '地址=+={\"name\":\"address\",\"type\":\"val\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注=+={\"name\":\"remark\",\"type\":\"more_text\",\"indexHide\":1}',
  `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '关键字=+={\"name\":\"keywords\",\"type\":\"more_text\",\"indexHide\":1}',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述=+={\"name\":\"description\",\"type\":\"more_text\",\"indexHide\":1}',
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '详细介绍=+={\"name\":\"details\",\"type\":\"rich_text\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间=+={\"name\":\"add_time\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1}',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改时间=+={\"name\":\"update_time\",\"type\":\"val\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"TimeFormat\":1}',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '应用' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for m_area
-- ----------------------------
DROP TABLE IF EXISTS `m_area`;
CREATE TABLE `m_area`  (
  `id` mediumint(6) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '地区名称',
  `status` tinyint(2) NOT NULL DEFAULT 0 COMMENT '应用状态=+={\"name\":\"statusPredefine\",\"type\":\"text\",\"list\":\"[\\\"1\\\"=>\\\"正常\\\",\\\"2\\\"=>\\\"冻结\\\"]\"}',
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '地区类型',
  `parent_id` mediumint(6) NOT NULL DEFAULT 0 COMMENT '上级区域',
  `show_sort` mediumint(6) NOT NULL DEFAULT 100 COMMENT '排序',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `type`(`type`) USING BTREE,
  INDEX `parentId`(`parent_id`) USING BTREE,
  INDEX `showSort`(`show_sort`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 810001 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '全国区域表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for m_device_visitor
-- ----------------------------
DROP TABLE IF EXISTS `m_device_visitor`;
CREATE TABLE `m_device_visitor`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '设备游客ID',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户ID=+={\"name\":\"user_id\",\"type\":\"val\",\"cuHide\":1,\"tableNote\":\"游客正式注册后会回写user_id统计转换率\"}',
  `device_code` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '设备唯一码',
  `app_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '应用=+={\"name\":\"appRecord\",\"type\":\"db\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"functionName\":\"getAppRecord\",\"modelName\":\"AppModel\",\"modelNamespace\":\"use common\\\\services\\\\application\\\\AppModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `source_channel_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '渠道=+={\"name\":\"sourceChannelRecord\",\"type\":\"db\",\"cuHide\":1,\"functionName\":\"getSourceChannelRecord\",\"modelName\":\"SourceChannelModel\",\"modelNamespace\":\"use common\\\\services\\\\sourcechannel\\\\SourceChannelModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `type` tinyint(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '设备分类=+={\"name\":\"typePredefine\",\"type\":\"text\",\"list\":\"[\\\"0\\\"=>\\\"未知\\\",\\\"1\\\"=>\\\"移动端\\\",\\\"2\\\"=>\\\"PC端\\\",\\\"3\\\"=>\\\"浏览器\\\"]\"}',
  `system` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设备系统',
  `model` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设备型号',
  `device_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设备描述=+={\"name\":\"device_desc\",\"type\":\"val\",\"indexHide\":1}',
  `district` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '地区',
  `ip` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'IP',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '生成时间=+={\"name\":\"add_time\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1,\"TimeSearch\":1}',
  `convert_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '转化时间=+={\"name\":\"convert_time\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1,\"TimeSearch\":1}',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `device_code`(`device_code`(16)) USING BTREE COMMENT '设备码快速检索索引仅前16个字符',
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '设备游客用户' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for m_log
-- ----------------------------
DROP TABLE IF EXISTS `m_log`;
CREATE TABLE `m_log`  (
  `id` bigint(20) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `level` int(11) NULL DEFAULT NULL COMMENT '等级=+={\"name\":\"levelPredefine\",\"type\":\"text\",\"list\":\"[\\\"1\\\"=>\\\"严重错误\\\",\\\"2\\\"=>\\\"警告\\\",\\\"3\\\"=>\\\"日志\\\",\\\"4\\\"=>\\\"普通日志\\\",\\\"5\\\"=>\\\"未知\\\"]\"}',
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
  `introduce` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '网站简介=+={\"name\":\"introduce\",\"type\":\"more_text\",\"indexHide\":1}',
  `seo_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO标题',
  `seo_keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO关键字',
  `seo_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'SEO描述=+={\"name\":\"seo_description\",\"type\":\"more_text\",\"indexHide\":1}',
  `telphone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '联系电话',
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号',
  `qq` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'QQ',
  `email` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱',
  `img_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'logo=+={\"name\":\"img_url\",\"type\":\"upload_image\",\"must\":1}',
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '封面=+={\"name\":\"\",\"type\":\"upload_image\",\"indexHide\":1}',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '详细介绍=+={\"name\":\"content\",\"type\":\"rich_text\"}',
  `about_us` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '关于我们=+={\"name\":\"aboutUs\",\"type\":\"rich_text\"}',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间=+={\"name\":\"add_time\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1}',
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '状态=+={\"name\":\"statusPredefine\",\"type\":\"text\",\"list\":\"[\\\"1\\\"=>\\\"启用\\\",\\\"2\\\"=>\\\"停用\\\"]\"}',
  `type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '类型=+={\"name\":\"typePredefine\",\"type\":\"text\",\"list\":\"[\\\"1\\\"=>\\\"常用\\\",\\\"2\\\"=>\\\"特殊\\\"]\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '网站设置' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for m_sms_email
-- ----------------------------
DROP TABLE IF EXISTS `m_sms_email`;
CREATE TABLE `m_sms_email`  (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '消息名称',
  `status` tinyint(2) NOT NULL DEFAULT 0 COMMENT '状态=+={\"name\":\"statusPredefine\",\"type\":\"text\",\"list\":\"[\\\"0\\\"=>\\\"不发送\\\",\\\"1\\\"=>\\\"发送成功\\\",\\\"2\\\"=>\\\"待发送\\\",\\\"3\\\"=>\\\"发送失败\\\"]\"}',
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '类型=+={\"name\":\"typePredefine\",\"type\":\"text\",\"list\":\"[\\\"1\\\"=>\\\"验证码\\\",\\\"2\\\"=>\\\"通知\\\",\\\"3\\\"=>\\\"消息\\\"]\"}',
  `app_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '应用=+={\"name\":\"appRecord\",\"type\":\"db\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"functionName\":\"getAppRecord\",\"modelName\":\"AppModel\",\"modelNamespace\":\"use common\\\\services\\\\application\\\\AppModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `object_id` int(11) NOT NULL DEFAULT 0 COMMENT '消息对象ID',
  `object_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '消息对象类型=+={\"name\":\"objectTypePredefine\",\"type\":\"text\",\"list\":\"[\\\"0\\\"=>\\\"无\\\",\\\"1\\\"=>\\\"用户\\\",\\\"2\\\"=>\\\"订单\\\"]\"}',
  `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '接收用户=+={\"name\":\"userRecord\",\"type\":\"db\",\"functionName\":\"getUserRecord\",\"modelName\":\"UserModel\",\"modelNamespace\":\"use common\\\\services\\\\user\\\\UserModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '邮箱',
  `other_emails` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '抄送邮箱=+={\"name\":\"other_emails\",\"type\":\"rich_text\",\"indexHide\":1}',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮件标题',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '发送内容=+={\"name\":\"content\",\"type\":\"rich_text\",\"indexHide\":1}',
  `params_json` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '附加参数=+={\"name\":\"params_json\",\"type\":\"rich_text\",\"indexHide\":1}',
  `add_time` int(10) NOT NULL DEFAULT 0 COMMENT '添加时间=+={\"name\":\"add_time\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1}',
  `send_time` int(10) NOT NULL DEFAULT 0 COMMENT '发送时间=+={\"name\":\"send_time\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1}',
  `send_num` tinyint(3) NOT NULL DEFAULT 0 COMMENT '发送次数=+={\"name\":\"send_num\",\"type\":\"val\",\"cuHide\":1}',
  `send_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '发送类型=+={\"name\":\"sendTypePredefine\",\"type\":\"text\",\"indexHide\":1,\"list\":\"[\\\"0\\\"=>\\\"未指定\\\",\\\"1\\\"=>\\\"用户发起\\\",\\\"2\\\"=>\\\"管理员发起\\\",\\\"3\\\"=>\\\"任务发起\\\"]\"}',
  `sms_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '邮件渠道=+={\"name\":\"smsTypePredefine\",\"type\":\"text\",\"indexHide\":1,\"list\":\"[\\\"0\\\"=>\\\"未知\\\",\\\"1\\\"=>\\\"阿里\\\",\\\"2\\\"=>\\\"163\\\",\\\"3\\\"=>\\\"qq\\\"]\"}',
  `template` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '发送模板=+={\"name\":\"template\",\"type\":\"rich_text\",\"cuHide\":1}',
  `feedback` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '发送反馈=+={\"name\":\"feedback\",\"type\":\"rich_text\",\"cuHide\":1,\"indexHide\":1}',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮件备注=+={\"name\":\"remark\",\"type\":\"rich_text\",\"indexHide\":1}',
  `is_delete` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否删除=+={\"name\":\"is_delete\",\"type\":\"title\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
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
  `app_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '应用=+={\"name\":\"appRecord\",\"type\":\"db\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"functionName\":\"getAppRecord\",\"modelName\":\"AppModel\",\"modelNamespace\":\"use common\\\\services\\\\application\\\\AppModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `status` tinyint(2) NOT NULL DEFAULT 0 COMMENT '状态=+={\"name\":\"statusPredefine\",\"type\":\"text\",\"list\":\"[\\\"0\\\"=>\\\"不发送\\\",\\\"1\\\"=>\\\"发送成功\\\",\\\"2\\\"=>\\\"待发送\\\",\\\"3\\\"=>\\\"发送失败\\\"]\"}',
  `object_id` int(11) NOT NULL DEFAULT 0 COMMENT '短信对象ID',
  `object_type` tinyint(3) NOT NULL DEFAULT 0 COMMENT '短信对象类型=+={\"name\":\"objectTypePredefine\",\"type\":\"text\",\"list\":\"[\\\"0\\\"=>\\\"无\\\",\\\"1\\\"=>\\\"用户\\\",\\\"2\\\"=>\\\"订单\\\"]\"}',
  `user_id` int(11) NOT NULL DEFAULT 0 COMMENT '接收用户=+={\"name\":\"userRecord\",\"type\":\"db\",\"functionName\":\"getUserRecord\",\"modelName\":\"UserModel\",\"modelNamespace\":\"use common\\\\services\\\\user\\\\UserModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `area_code` int(10) NOT NULL DEFAULT 0 COMMENT '手机区号=+={\"name\":\"area_code\",\"type\":\"title\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `mobile` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '手机号',
  `other_mobiles` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '其他接收手机号=+={\"name\":\"other_mobiles\",\"type\":\"more_text\",\"indexHide\":1}',
  `content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '发送内容=+={\"name\":\"content\",\"type\":\"more_text\",\"indexHide\":1}',
  `params_json` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '附加参数=+={\"name\":\"params_json\",\"type\":\"more_text\",\"indexHide\":1}',
  `send_time` int(10) NOT NULL DEFAULT 0 COMMENT '发送时间=+={\"name\":\"send_time\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1,\"TimeSearch\":1}',
  `send_num` tinyint(3) NOT NULL DEFAULT 0 COMMENT '发送次数=+={\"name\":\"send_num\",\"type\":\"val\",\"cuHide\":1}',
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '类型=+={\"name\":\"typePredefine\",\"type\":\"text\",\"list\":\"[\\\"1\\\"=>\\\"验证码\\\",\\\"2\\\"=>\\\"通知\\\",\\\"3\\\"=>\\\"消息\\\"]\"}',
  `send_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '发送类型=+={\"name\":\"sendTypePredefine\",\"type\":\"text\",\"indexHide\":1,\"list\":\"[\\\"0\\\"=>\\\"未指定\\\",\\\"1\\\"=>\\\"用户发起\\\",\\\"2\\\"=>\\\"管理员发起\\\",\\\"3\\\"=>\\\"任务发起\\\"]\"}',
  `sms_type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '短信渠道=+={\"name\":\"smsTypePredefine\",\"type\":\"text\",\"indexHide\":1,\"list\":\"[\\\"0\\\"=>\\\"系统判定渠道\\\",\\\"1\\\"=>\\\"阿里\\\",\\\"2\\\"=>\\\"腾讯\\\",\\\"6\\\"=>\\\"互亿\\\",\\\"9\\\"=>\\\"其他\\\"]\"}',
  `template` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '发送模板=+={\"name\":\"template\",\"type\":\"more_text\",\"cuHide\":1}',
  `feedback` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '发送反馈=+={\"name\":\"feedback\",\"type\":\"more_text\",\"cuHide\":1,\"indexHide\":1}',
  `remark` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '短信备注=+={\"name\":\"remark\",\"type\":\"more_text\",\"indexHide\":1}',
  `add_time` int(10) NOT NULL DEFAULT 0 COMMENT '添加时间=+={\"name\":\"add_time\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1,\"TimeSearch\":1}',
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
  `app_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '应用=+={\"name\":\"appRecord\",\"type\":\"db\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"functionName\":\"getAppRecord\",\"modelName\":\"AppModel\",\"modelNamespace\":\"use common\\\\services\\\\application\\\\AppModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '渠道名称',
  `channel_code` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '渠道唯一码=+={\"name\":\"channel_code\",\"type\":\"val\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `status` tinyint(2) NOT NULL DEFAULT 0 COMMENT '渠道状态=+={\"name\":\"statusPredefine\",\"type\":\"text\",\"list\":\"[\\\"1\\\"=>\\\"正常\\\",\\\"2\\\"=>\\\"冻结\\\"]\"}',
  `type` tinyint(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '渠道分类=+={\"name\":\"typePredefine\",\"type\":\"text\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"list\":\"[\\\"0\\\"=>\\\"未知\\\",\\\"1\\\"=>\\\"普通\\\",\\\"2\\\"=>\\\"特别\\\"]\"}',
  `img_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '渠道配图=+={\"name\":\"img_url\",\"type\":\"upload_image\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `province_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '省=+={\"name\":\"provinceRecord\",\"type\":\"db\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"functionName\":\"getProvinceRecord\",\"modelName\":\"AreaModel\",\"modelNamespace\":\"use common\\\\services\\\\Area\\\\AreaModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `city_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '市=+={\"name\":\"cityRecord\",\"type\":\"db\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"functionName\":\"getCityRecord\",\"modelName\":\"AreaModel\",\"modelNamespace\":\"use common\\\\services\\\\Area\\\\AreaModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `area_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '区=+={\"name\":\"areaRecord\",\"type\":\"db\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"functionName\":\"getAreaRecord\",\"modelName\":\"AreaModel\",\"modelNamespace\":\"use common\\\\services\\\\Area\\\\AreaModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `address` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '地址=+={\"name\":\"address\",\"type\":\"val\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `liaison` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '联络人=+={\"name\":\"liaison\",\"type\":\"val\"}',
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '电话=+={\"name\":\"phone\",\"type\":\"val\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号=+={\"name\":\"mobile\",\"type\":\"val\"}',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱=+={\"name\":\"email\",\"type\":\"val\"}',
  `qq` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'QQ=+={\"name\":\"qq\",\"type\":\"val\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `weixin` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微信=+={\"name\":\"weixin\",\"type\":\"val\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `weibo` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '微博=+={\"name\":\"weibo\",\"type\":\"val\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注=+={\"name\":\"remark\",\"type\":\"more_text\",\"indexHide\":1}',
  `keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '关键字=+={\"name\":\"keywords\",\"type\":\"more_text\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '描述=+={\"name\":\"description\",\"type\":\"more_text\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL COMMENT '详细介绍=+={\"name\":\"details\",\"type\":\"rich_text\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间=+={\"name\":\"add_time\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1,\"TimeSearch\":1}',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改时间=+={\"name\":\"update_time\",\"type\":\"val\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"TimeFormat\":1}',
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
  `add_time` int(11) NOT NULL DEFAULT 0 COMMENT '添加时间=+={\"name\":\"add_time\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1}',
  `update_time` int(11) NOT NULL DEFAULT 0 COMMENT '更新时间=+={\"name\":\"update_time\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1}',
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
  `app_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '应用=+={\"name\":\"appRecord\",\"type\":\"db\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"functionName\":\"getAppRecord\",\"modelName\":\"AppModel\",\"modelNamespace\":\"use common\\\\services\\\\application\\\\AppModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `name` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '昵称',
  `area_code` int(10) NOT NULL DEFAULT 0 COMMENT '手机区号=+={\"name\":\"area_code\",\"type\":\"title\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `mobile` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号=+={\"name\":\"mobile\",\"type\":\"val\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `username` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户名=+={\"name\":\"username\",\"type\":\"val\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `login_password` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '密码=+={\"name\":\"login_password\",\"type\":\"val\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `status` tinyint(2) NOT NULL DEFAULT 0 COMMENT '状态=+={\"name\":\"statusPredefine\",\"type\":\"text\",\"tablenote\":\"用户状态避免修改值,-1,1,2以免导致意外判断问题，扩展后自行维护相应地方\",\"list\":\"[\\\"1\\\"=>\\\"正常\\\",\\\"2\\\"=>\\\"冻结\\\"]\"}',
  `type` tinyint(2) NOT NULL DEFAULT 0 COMMENT '用户类型=+={\"name\":\"typePredefine\",\"type\":\"text\",\"list\":\"[\\\"1\\\"=>\\\"注册用户\\\",\\\"2\\\"=>\\\"特权用户\\\"]\",\"tableNote\":\"注意创建用户时明确指定type且不能使用-1，-1与游客冲突\"}',
  `level` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '等级=+={\"name\":\"level\",\"type\":\"val\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `realname` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '真实姓名=+={\"name\":\"realname\",\"type\":\"val\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `email` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱=+={\"name\":\"email\",\"type\":\"val\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1}',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像=+={\"name\":\"avatar\",\"type\":\"upload_image\"}',
  `introduce` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '自我介绍=+={\"name\":\"introduce\",\"type\":\"more_text\",\"indexHide\":1}',
  `sex` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '性别=+={\"name\":\"sexPredefine\",\"type\":\"text\",\"indexHide\":1,\"list\":\"[\\\"0\\\"=>\\\"未知\\\",\\\"1\\\"=>\\\"男\\\",\\\"2\\\"=>\\\"女\\\"]\"}',
  `birthday` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '生日=+={\"name\":\"birthday\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1,\"indexHide\":1}',
  `district` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '地区=+={\"name\":\"district\",\"type\":\"val\",\"indexHide\":1}',
  `title` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头衔=+={\"name\":\"title\",\"type\":\"val\",\"indexHide\":1}',
  `token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'token=+={\"name\":\"token\",\"type\":\"val\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"table_remark\":\"使用login_bind的token,此字段为预留\"}',
  `source_channel_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '渠道=+={\"name\":\"sourceChannelRecord\",\"type\":\"db\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"functionName\":\"getSourceChannelRecord\",\"modelName\":\"SourceChannelModel\",\"modelNamespace\":\"use common\\\\services\\\\sourcechannel\\\\SourceChannelModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '注册时间=+={\"name\":\"add_time\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1,\"TimeSearch\":1}',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改时间=+={\"name\":\"update_time\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1,\"TimeSearch\":1}',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 86 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户' ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for m_user_login_bind
-- ----------------------------
DROP TABLE IF EXISTS `m_user_login_bind`;
CREATE TABLE `m_user_login_bind`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `bind_key` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '类型绑定key',
  `type` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录类型=+={\"name\":\"typePredefine\",\"type\":\"text\",\"list\":\"[\\\"0\\\"=>\\\"未知\\\",\\\"1\\\"=>\\\"用户名登录\\\",\\\"2\\\"=>\\\"手机号登录\\\",\\\"3\\\"=>\\\"邮箱登录\\\"]\"}',
  `app_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '应用=+={\"name\":\"appRecord\",\"type\":\"db\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"functionName\":\"getAppRecord\",\"modelName\":\"AppModel\",\"modelNamespace\":\"use common\\\\services\\\\application\\\\AppModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间=+={\"name\":\"add_time\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1,\"TimeSearch\":1}',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改时间=+={\"name\":\"update_time\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1,\"TimeSearch\":1}',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `bind_unique`(`bind_key`, `type`, `app_id`) USING BTREE COMMENT '严格唯一索引key_type_app_id',
  INDEX `user_id`(`user_id`) USING BTREE COMMENT '用户索引'
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户登录绑定' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for m_user_login_bind_third
-- ----------------------------
DROP TABLE IF EXISTS `m_user_login_bind_third`;
CREATE TABLE `m_user_login_bind_third`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `bind_key` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '类型绑定key',
  `type` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '第三方绑定类型=+={\"name\":\"typePredefine\",\"type\":\"text\",\"list\":\"[\\\"0\\\"=>\\\"未知\\\",\\\"10\\\"=>\\\"微信登录\\\",\\\"20\\\"=>\\\"QQ登录\\\",\\\"30\\\"=>\\\"微博登录\\\",\\\"40\\\"=>\\\"Apple登录\\\"]\"}',
  `app_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '应用=+={\"name\":\"appRecord\",\"type\":\"db\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"functionName\":\"getAppRecord\",\"modelName\":\"AppModel\",\"modelNamespace\":\"use common\\\\services\\\\application\\\\AppModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `bind_unionid` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '绑定unionid(如微信)',
  `bind_num` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '登录平台号',
  `bind_nickname` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '昵称',
  `bind_avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像',
  `bind_sex` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '性别',
  `bind_birthday` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '生日',
  `bind_district` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '城市',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间=+={\"name\":\"add_time\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1,\"TimeSearch\":1}',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改时间=+={\"name\":\"update_time\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1,\"TimeSearch\":1}',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `bind_unique`(`bind_key`, `type`, `app_id`) USING BTREE COMMENT '第三方绑定唯一索引',
  INDEX `user_id`(`user_id`) USING BTREE COMMENT '用户索引'
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户第三方登录绑定' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for m_user_login_device
-- ----------------------------
DROP TABLE IF EXISTS `m_user_login_device`;
CREATE TABLE `m_user_login_device`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `device_code` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '设备码(type=3浏览器对UA进行MD5)',
  `app_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '应用=+={\"name\":\"appRecord\",\"type\":\"db\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"functionName\":\"getAppRecord\",\"modelName\":\"AppModel\",\"modelNamespace\":\"use common\\\\services\\\\application\\\\AppModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `type` tinyint(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '设备分类=+={\"name\":\"typePredefine\",\"type\":\"text\",\"list\":\"[\\\"0\\\"=>\\\"未知\\\",\\\"1\\\"=>\\\"移动端\\\",\\\"2\\\"=>\\\"PC端\\\",\\\"3\\\"=>\\\"浏览器\\\"]\"}',
  `system` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设备系统',
  `model` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设备型号',
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设备登录token',
  `device_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设备描述',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间=+={\"name\":\"add_time\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1,\"TimeSearch\":1}',
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '修改时间=+={\"name\":\"update_time\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1,\"TimeSearch\":1}',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `device_unique`(`user_id`, `device_code`, `app_id`) USING BTREE COMMENT '设备唯一id'
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户登录设备' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for m_user_login_log
-- ----------------------------
DROP TABLE IF EXISTS `m_user_login_log`;
CREATE TABLE `m_user_login_log`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `app_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '应用=+={\"name\":\"appRecord\",\"type\":\"db\",\"cuHide\":1,\"indexHide\":1,\"viewHide\":1,\"functionName\":\"getAppRecord\",\"modelName\":\"AppModel\",\"modelNamespace\":\"use common\\\\services\\\\application\\\\AppModel;\",\"joinTableId\":\"id\",\"showName\":\"name\",\"selectFeild\":\"id,name\"}',
  `bind_id` int(10) UNSIGNED NULL DEFAULT 0 COMMENT '绑定登录方式ID',
  `device_id` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '设备id',
  `type` tinyint(2) UNSIGNED NOT NULL DEFAULT 0 COMMENT '设备分类=+={\"name\":\"typePredefine\",\"type\":\"text\",\"list\":\"[\\\"1\\\"=>\\\"移动端\\\",\\\"2\\\"=>\\\"PC端\\\",\\\"3\\\"=>\\\"浏览器\\\"]\"}',
  `login_type` smallint(5) UNSIGNED NOT NULL DEFAULT 0 COMMENT '登录类型=+={\"tableNote\":\"注意这里的type和login_bind的type不完全相同，这里区分了登录的形式\",\"name\":\"typePredefine\",\"type\":\"text\",\"list\":\"[\\\"0\\\"=>\\\"未设置\\\",\\\"1\\\"=>\\\"用户名登录\\\",\\\"2\\\"=>\\\"手机验证码登录\\\",\\\"3\\\"=>\\\"邮箱登录\\\",\\\"11\\\"=>\\\"微信 App登录\\\",\\\"12\\\"=>\\\"微信 Web登录\\\",\\\"21\\\"=>\\\"QQ App登录\\\",\\\"22\\\"=>\\\"QQ Web登录\\\",\\\"31\\\"=>\\\"微博 APP登录\\\",\\\"41\\\"=>\\\"Apple登录\\\"]\"}',
  `device_code` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '设备码(type=3浏览器对UA进行MD5)',
  `system` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设备系统',
  `model` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设备型号',
  `district` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '地区',
  `device_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '设备描述',
  `ip` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'ip',
  `add_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '添加时间=+={\"name\":\"add_time\",\"type\":\"val\",\"cuHide\":1,\"TimeFormat\":1,\"TimeSearch\":1}',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `user_id`(`user_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户登录记录' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
