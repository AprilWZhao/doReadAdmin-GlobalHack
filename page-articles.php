<?php 
session_start();
//pr($_SESSION); die;
	if( isset($_SESSION['user_session']) )
	{
			$creds = array();
			$creds['user_login'] = $_SESSION['user_session']['user_login'];
			$creds['user_password'] = $_SESSION['user_session']['user_password'];
			$creds['remember'] = $_SESSION['user_session']['remember'];
			
			session_unset('user_session');
			$user = wp_signon( $creds, true );
			$current_user = $user->data->ID;
			if($current_user)
			{
				wp_set_auth_cookie($current_user);
			}	
			if (is_wp_error($user))
			{
				//echo $user->get_error_message();
			}
	}
get_header();
?>
<?php get_sidebar(); ?>
<!--main content start-->
      <section id="main-content">
          <section class="wrapper">
            <div class="col-lg-2">
           	 <!--<h3><i class="fa fa-angle-right"></i> Events</h3>-->
             </div>
        <div class="col-lg-10">
		<button class="btn btn-primary btn-lg pull-right" data-toggle="modal" data-target="#addDoRead" style="margin-top: 10px; margin-bottom:10px;">
		New DoRead
		</button>
        </div>
            <div class="row mt">
                  <div class="col-md-12">
                      <div class="content-panel">
                          <table class="table table-striped table-advance table-hover user_ev_meta">
	                  	  	  <!--<h4><i class="fa fa-angle-right"></i> Current Events</h4>
	                  	  	  <hr>-->
                              <thead>
                              <tr>
                                  <th><i class="fa fa-bullhorn"></i> Article</th>
                                  <th><i class="fa fa-question-circle"></i> Date</th>
                                  <?php /*?><th><i class="fa fa-bullhorn"></i> URL</th><?php */?>
                                  <th><i class="fa fa-bullhorn"></i> Action</th>
                                  <th></th>
                              </tr>
                              </thead>
                              <tbody>
	  <?php 
	  $user_ID = get_current_user_id();
      /* Call WP's "get_results" on your query and create the array */
     global $wpdb;
     $check_row = $wpdb->get_row("SELECT * FROM `wp_posts` WHERE post_author =".$user_ID."&& post_type = 'doread'");
	 if($check_row){
	 	$doreads = $wpdb->get_results("SELECT * FROM `wp_posts` WHERE post_author =".$user_ID."&& post_type = 'doread'") or die(mysql_error());
		
		
		
		//Don't need I don't think...this is for the json array
            foreach($doreads as $doread){
				/*print_r($doread);
				die();*/
				$article = $doread->post_title;
				$date = $doread->post_date;
				//$url = $json_info->firstName . " " . $json_info->lastName;
				
				
				
				
				//This is where it's interesting the data. So, from
				echo "<tr class='user_event_".$doread->ID."'>
                   	<td><a href='".get_bloginfo('url')."/stem_counter/?eid=$doread->ID'>" . $article . "</a></td>
					<td>".	$date	."</td>
                 
	
					<td>" ?>
					<div class="btn-group">
					<button type="button" class="btn btn-theme03">Action</button>
					<button type="button" class="btn btn-theme03 dropdown-toggle" data-toggle="dropdown">
					<span class="caret"></span>
					<span class="sr-only">Toggle Dropdown</span>
					</button>
					<ul class="dropdown-menu" role="menu">
					<li><a href="<?php echo get_bloginfo('url')."/article/?eid=".$doread->event_id; ?>">Go</a></li>
					<li><a href="#" onclick="return event_archive('<?php echo $doread->event_id; ?>')">Archive</a></li>
					<li class="divider"></li>
					<li><a href="<?php echo "#" ?>" rel="<?php echo $doread->event_id; ?>" 
					title="<?php echo $doread->event_name; ?>" class="delEvent">Delete</a></li>
					</ul>
            		</div>      		
                	</td>
           			</tr>
			<?php } }else{
				echo "<h2>No Event</h2>";
			} ?>
                     </tbody>
                     </table>
                     </div><!-- /content-panel -->
                  </div><!-- /col-md-12 -->
              
              
              <!--ARCHIVED Articles-->
              </div><!-- /row -->
                  <div class="col-md-12" style="margin-top: 10px; margin-right: 20px;">
                         <a class="pull-right" href="<?php echo get_bloginfo('url'); ?>/archive">Archived Articles</a>
                  </div><!-- /col-md-12 -->
                  <div id="feedback"></div>

		</section><!--/wrapper -->
      </section><!-- /MAIN CONTENT -->

      <!--main content end-->
		<!-- Modal -->
	<div class="modal fade" id="addDoRead" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	        <h4 class="modal-title" id="myModalLabel">Add New DoRead</h4>
	      </div>
	      <div class="modal-body">
         <!-- <div class="form-panel">-->
		<form class="form-horizontal style-form" id="newCustomerForm" method="post" onsubmit="return add_doread();">
		<div class="form-top">
		</div>
	
		<div class="form-group">
		<label class="col-sm-2 col-sm-2 control-label">Title</label>
		<div class="col-sm-8">
		<input type="text" name="articleTitle" class="form-control" id="articleTitle">
		</div>
		</div>
		
		<div class="form-group">
		<label class="col-sm-2 col-sm-2 control-label">URL</label>
		<div class="col-sm-8">
		<input type="text" name="articleURL" class="form-control" id="articleURL">
		</div>
		</div>
        
        <div class="form-group">
		<label class="col-sm-2 col-sm-2 control-label" required>Word Count</label>
		<div class="col-sm-3">
		<input type="text"  class="form-control" name="wordCount" placeholder="e.g. 1056" id="wordCount">
		</div>
		</div>
		
		<div class="form-group">
		<label class="col-sm-2 col-sm-2 control-label">First Comment</label>
		<div class="col-sm-8">
		<input type="text" name="firstComment" class="form-control" id="firstComment">
		</div>
		</div>
		
		<!--</div>-->
		<div class="modal-footer">
		<div class="pull-left">
		*required
		</div>
		<button type="button" class="btn btn-default" data-dismiss="modal">
		Cancel
		</button>
		<input type="hidden" name="action" value="addCustomer"/>
		<button type="submit" name="action" value="addCustomer" class="btn btn-primary" >
		Let's Go!
		</button>
		</div>
		</form>
	      
	    </div>
	  </div>
	</div>     
    </div>
<?php include('inc-forms/edit-event-form.php'); ?>    
<?php get_footer(); ?>