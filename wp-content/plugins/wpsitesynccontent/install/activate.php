<?php

/*
 * Performs installation process
 * @package Sync
 * @author Dave Jesch
 */

class SyncActivate
{
	// TODO: move this to the /classes/ directory

	const OPTION_ACTIVATED_LIST = 'spectrom_sync_activated';

	/*
	 * called on plugin activation; performs all installation tasks
	 * @param boolean $network TRUE when activating network-wide on MultiSite; otherwise FALSE
	 */
	public function plugin_activation($network = FALSE)
	{
SyncDebug::log(__METHOD__.'(' . var_export($network, TRUE) . '):' . __LINE__);
SyncDebug::log(__METHOD__.'():' . __LINE__ . ' multisite=' . (is_multisite() ? 'TRUE' : 'FALSE'));
		if ($network && is_multisite()) {
			$activated = get_site_option(self::OPTION_ACTIVATED_LIST, array());
SyncDebug::log(__METHOD__.'():' . __LINE__ . ' activated=' . implode(',', $activated));

			$current_blog = get_current_blog_id();
			$blogs = $this->_get_all_blogs();
			foreach ($blogs as $blog_id) {
//SyncDebug::log(__METHOD__.'():' . __LINE__ . ' switching to blog id=' . $blog_id);
				switch_to_blog($blog_id);
				$this->_site_activation();					// still need to perform activation in case db structures changed
				if (!in_array($blog_id, $activated)) {		// add only if not already present
					$activated[] = abs($blog_id);
				} else {
//SyncDebug::log(__METHOD__.'():' . __LINE__ . ' blog already marked as initialized');
				}
			}
//SyncDebug::log(__METHOD__.'():' . __LINE__ . ' switching back to ' . $current_blog);
			switch_to_blog($current_blog);
SyncDebug::log(__METHOD__.'():' . __LINE__ . ' switched back to ' . $current_blog . ' activated=' . implode(',', $activated));
			update_site_option(self::OPTION_ACTIVATED_LIST, $activated);
//SyncDebug::log(__METHOD__.'():' . __LINE__ . ' updated "' . self::OPTION_ACTIVATED_LIST . '" with ' . implode(',', $activated));
		} else {
SyncDebug::log(__METHOD__.'():' . __LINE__ . ' single site activation');
			$this->_site_activation();
		}

		return TRUE;
	}

	/**
	 * Checks that plugin has been activated on all blogs within the site
	 */
	public function plugin_activate_check()
	{
SyncDebug::log(__METHOD__.'()');
		$current_blog = get_current_blog_id();						// get this so we can switch back later

		// check to see that all blogs have been activated
		$activated = get_site_option(self::OPTION_ACTIVATED_LIST, array());
SyncDebug::log(__METHOD__.'():' . __LINE__ . ' previously activated sites: ' . implode(',', $activated));

		$blogs = $this->_get_all_blogs();
		// TODO: use array_diff() to see if the loop is necessary
		foreach ($blogs as $blog_id) {
			$blog_id = abs($blog_id);
//SyncDebug::log(__METHOD__.'():' . __LINE__ . ' blog=' . $blog_id);
			if (!in_array($blog_id, $activated)) {
				// plugin not activated on this blog
//SyncDebug::log(__METHOD__.'():' . __LINE__ . ' switching to ' . $blog_id);
				switch_to_blog($blog_id);
				restore_current_blog();			// #247 restore blog state
				$this->_site_activation();
				$activated[] = $blog_id;
			}
//else SyncDebug::log(__METHOD__.'():' . __LINE__ . ' site ' . $blog_id . ' already activated');
		}

		switch_to_blog($current_blog);			// #247 switch back to blog
		restore_current_blog();					// switch back to original blog #224- Yoast needs this
 
		// save activated list for later checks
		update_site_option(self::OPTION_ACTIVATED_LIST, $activated);
SyncDebug::log(__METHOD__.'():' . __LINE__ . ' updated "' . self::OPTION_ACTIVATED_LIST . '" with ' . implode(',', $activated));
	}

	/**
	 * Obtains a list of all blogs known to the site
	 * @return array List of integers representing the blogs on the MultiSite
	 */
	private function _get_all_blogs()
	{
		global $wpdb;
		$sql = "SELECT `blog_id`
			FROM `{$wpdb->blogs}`";
		$blogs = $wpdb->get_col($sql);
//SyncDebug::log(__METHOD__.'():' . __LINE__ . ' blogs=' . implode(',', $blogs));
		return $blogs;
	}

	/**
	 * Performs activation steps for a single site. Create databases and write options.
	 */
	private function _site_activation()
	{
		$this->create_database_tables();
		$this->create_options();
	}

	/**
	 * Returns array containing information on database tables
	 * @return array Database information
	 */
	protected function get_table_data()
	{
		$ret = array(
			// table names will be prefixed with "{$wpdb->prefix}spectrom_"
			'sync_log' =>
				"CREATE TABLE `sync_log` (
					`id` 				INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
					`post_id` 			BIGINT(20) UNSIGNED NOT NULL,
					`post_title` 		TEXT NOT NULL,
					`push_date` 		TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`operation`			VARCHAR(32) NOT NULL,
					`source_user` 		BIGINT(20) UNSIGNED NOT NULL,
					`source_site` 		VARCHAR(200) NOT NULL,
					`source_site_key`	VARCHAR(40) NOT NULL,
					`target_user`		BIGINT(20) UNSIGNED NOT NULL,
					`type`				VARCHAR(4) NOT NULL DEFAULT 'recv',

					PRIMARY KEY (`id`),
					INDEX `post_id` (`post_id`),
					INDEX `type` (`type`)
				) ",
			'sync' =>
				"CREATE TABLE `sync` (
					`sync_id` 			INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
					`site_key` 			VARCHAR(60) NOT NULL,
					`source_content_id` BIGINT(20) UNSIGNED NOT NULL,
					`target_content_id`	BIGINT(20) UNSIGNED NOT NULL,
					`target_site_key`	VARCHAR(60) NULL DEFAULT '',
					`content_type`		VARCHAR(32) NOT NULL DEFAULT 'post',
					`last_update`		DATETIME NOT NULL,
					`wp_version`		VARCHAR(20) NOT NULL,
					`sync_version`		VARCHAR(20) NOT NULL,

					PRIMARY KEY (`sync_id`),
					INDEX `source_content_id` (`source_content_id`),
					INDEX `target_content_id` (`target_content_id`),
					INDEX `content_type` (`content_type`)
				) ",
			'sources' =>
				"CREATE TABLE `sync_sources` (
					`id`				INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
					`domain`			VARCHAR(200) NOT NULL,
					`site_key`			VARCHAR(60) NOT NULL DEFAULT '',
					`auth_name`			VARCHAR(60) NOT NULL,
					`token`				VARCHAR(60) NOT NULL,
					`allowed`			TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
					PRIMARY KEY (`id`),
					INDEX `site_key` (`site_key`),
					INDEX `allowed` (`allowed`)
				) ",
			// TODO: merge this into the `sync` table
			'sync_media' =>
				"CREATE TABLE `sync_media` (
					`id`				INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
					`site_key` 			VARCHAR(60) NOT NULL,
					`remote_media_name` VARCHAR(255) NOT NULL,
					`local_media_name`	VARCHAR(255) NOT NULL,

					PRIMARY KEY (`id`),
					INDEX `site_key` (`site_key`)
				) "
		);

		return $ret;
	}

	/**
	 * Returns array containing information on database alterations
	 * @return array Database alter information
	 */
	protected function get_alter_data()
	{
		$ret = array();

		// v1.5 add `type` column to `spectrom_sync_log` table
		$alter_data = new stdClass();
		$alter_data->version = '1.5';
		$alter_data->table = 'spectrom_sync_log';
		$alter_data->sql = "ALTER TABLE `spectrom_sync_log`
				ADD COLUMN `type` VARCHAR(4) NOT NULL DEFAULT 'recv' AFTER `target_user`";
		$ret[] = $alter_data;

		// v1.5.5 remove the `spectrom_sync_media` table
		$alter_data = new stdClass();
		$alter_data->version = '1.5.5';
		$alter_data->table = 'spectrom_sync_media';
		$alter_data->sql = "DROP TABLE IF EXISTS `spectrom_sync_media`";
		$ret[] = $alter_data;

		// TODO: add a ->migrate property with 'up' or 'down' values to allow for
		// TODO: upgrading AND downgrading database change operations

		return $ret;
	}

	/**
	 * Creates the default options and generates a site_key value if activated for the first time.
	 */
	protected function create_options()
	{
		$site_key = SyncOptions::get('site_key', '');
SyncDebug::log(__METHOD__.'():' . __LINE__ . ' site key=' . $site_key);
		if ('' === $site_key) {
			$model = new SyncModel();
			SyncOptions::set('site_key', $model->generate_site_key());
SyncDebug::log(__METHOD__.'():' . __LINE__ . ' site key=' . SyncOptions::get('site_key'));
		}
		$date = SyncOptions::get('installed');
		if (empty($date)) {
			SyncOptions::set('installed', $this->get_install_date());
		}
		SyncOptions::save_options();
	}

	/**
	 * Determine the install date. Use the current date, then look in tables for an earlier date
	 * @return string $date The date/time stamp that WPSiteSync was instaled
	 */
	public function get_install_date()
	{
		$date = current_time('mysql');

		// look in the sync table
		global $wpdb;
		$sql = "SELECT `last_update`
					FROM `{$wpdb->prefix}spectrom_sync`
					ORDER BY `last_update` ASC
					LIMIT 1";
		$check_date = $wpdb->get_col($sql);
		if (is_array($check_date) && count($check_date) > 0 && $check_date[0] < $date)
			$date = $check_date[0];

		// look in the log table
		$sql = "SELECT `push_date`
					FROM `{$wpdb->prefix}spectrom_sync_log`
					ORDER BY `push_date` ASC
					LIMIT 1";
		$check_date = $wpdb->get_col($sql);
		if (is_array($check_date) && count($check_date) > 0 && $check_date < $date)
			$date = $check_date;

		return $date;
	}

	/**
	 * Runs dbDelta based on the table data from get_table_data() to create the database tables.
	 */
	protected function create_database_tables()
	{
SyncDebug::log(__METHOD__.'():' . __LINE__);
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$charset_collate = $wpdb->get_charset_collate();

		// Hack to reduce errors. Removing backticks makes the dbDelta function work better. #164
		add_filter('dbdelta_create_queries', array($this, 'filter_dbdelta_queries'));
		add_filter('wp_should_upgrade_global_tables', '__return_true');

		$errors = $wpdb->show_errors(FALSE);				// disable errors #164
		$tables = $this->get_table_data();
		foreach ($tables as $table => $sql) {
			$sql = str_replace('CREATE TABLE `', 'CREATE TABLE `' . $wpdb->prefix . 'spectrom_', $sql);
			$sql .= $charset_collate;
//SyncDebug::log(__METHOD__.'():' . __LINE__ . ' sql=' . $sql);

//			ob_start();
			$ret = dbDelta($sql);
//			$res = ob_get_clean();
//SyncDebug::log(__METHOD__.'() dbDelta() results: ' . $res);
		}

		// process database alterations
		$db_version = SyncOptions::get('version', '1.0');
		$alters = $this->get_alter_data();
		foreach ($alters as $alter_data) {
			if (version_compare($db_version, $alter_data->version, 'lt')) {
SyncDebug::log(__METHOD__.'():' . __LINE__ . ' performing alter for v' . $alter_data->version);
				$sql = str_replace('`' . $alter_data->table . '`',
					'`' . $wpdb->prefix . $alter_data->table . '`',
					$alter_data->sql);
SyncDebug::log(__METHOD__.'():' . __LINE__ . ' sql=' . $sql);
				$wpdb->query($sql);
			}
		}

		$wpdb->show_errors($errors);						// reset errors #164
SyncDebug::log(__METHOD__.'():' . __LINE__ . ' - done');

		// set the 'version' to the current plugin version #218
		SyncOptions::set('version', WPSiteSyncContent::PLUGIN_VERSION);
		SyncOptions::save_options();
	}

	/**
	 * Filters the CREATE TABLE statements, removing backticks so that the dbDelta parser doesn't throw errors
	 * @param array $c_queries An array of SQL statements
	 * @return array Modified statements
	 */
	public function filter_dbdelta_queries($c_queries)
	{
		$ret_queries = array();
		foreach ($c_queries as $query) {
			$ret_queries[] = str_replace('`', '', $query);
		}
		return $ret_queries;
	}
}

// EOF
