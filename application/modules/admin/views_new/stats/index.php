<!-- Page content -->
	<div id="page">
		<!-- Wrapper -->
		<div class="wrapper">
			<section class="column width6 first">					
                <div id="tabs" class="ui-tabs ui-widget ui-widget-content ui-corner-all">
                    <ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
                        	<li class="ui-state-default ui-corner-top ui-tabs<?=$page=='general'? '-selected' : ''?> ui-state-active">
                        		<a class="corner-tl" href="/admin/stats/general">General</a>
                        	</li>
                        	<li class="ui-state-default ui-corner-top ui-tabs<?=$page=='sources'? '-selected' : ''?> ui-state-active">
                        		<a class="" href="/admin/stats/sources">Traffic sources</a>
                        	</li>
                        	<li class="ui-state-default ui-corner-top ui-tabs<?=$page=='content'? '-selected' : ''?> ui-state-active">
                        		<a class="" href="/admin/stats/content">Content</a>
                        	</li>
                        	<li class="ui-state-default ui-corner-top ui-tabs<?=$page=='users'? '-selected' : ''?> ui-state-active">
                        		<a class="" href="/admin/stats/users">Users</a>
                        	</li>
                        	<li class="ui-state-default ui-corner-top ui-tabs<?=$page=='newsfeeds'? '-selected' : ''?> ui-state-active">
                        		<a class="" href="/admin/stats/newsfeeds">Newsfeeds</a>
                        	</li>
                        	<li class="ui-state-default ui-corner-top ui-tabs<?=$page=='links'? '-selected' : ''?> ui-state-active">
                        		<a class="corner-tr" href="/admin/stats/links">Links</a>
                        	</li>
                    </ul>
                </div>
			</section>
			<!-- End of Left column/section -->
			<aside class="column width2">
				<div class="content-box">
						<header style="cursor: s-resize; ">
							<h3>Visitors</h3>
						</header>
						<section>
							<strong>New: </strong><span>0</span>
							<br/>
							<strong>Returning: </strong><span>0</span>
							<br/>
							<strong>Mobile: </strong><span></span><small>0 %</small>
						</section>
				</div>
			</aside>
		</div>
		<!-- End of Wrapper -->
	</div>
	<!-- End of Page content --> 