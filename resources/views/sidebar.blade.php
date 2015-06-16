@section("sidebar")
<?php
	$active = array("","","","","","","","","");
	$key = explode("?",str_replace("/", "?", Request::path()));
	switch ($key[0]) {
		case 'home': $active[0] = "active"; break;
		case 'facility': $active[1] = "active"; break;
		case 'permission': $active[2] = "active"; break;
		case 'role':
		case 'privilege':
		case 'authorization': $active[3] = "active"; break;
		case 'user': 
		case 'facilityType': 
		case 'facilityOwner': 
		case 'title': 
		case 'county': 
		case 'constituency':
		case 'town':$active[4] = "active"; break;
		case 'labLevel': 
		case 'labAffiliation': 
		case 'labType':
		case 'lab':
		case 'auditType':
		case 'section':
		case 'auditField':
		case 'review':
		case 'answer':$active[5] = "active"; break;
		case 'note': 
		case 'assessment':
		case 'question': 
		
	}
?>
	    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                    <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                       
                      
                        <li>
                            <a href="#"><i class="fa fa-files-o fa-fw"></i> {{ Lang::choice('messages.hiv-logbook', 1) }}<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level collapse">
                                <li><a href=""><i class="fa fa-tag"></i> {{ Lang::choice('messages.admin', 2) }}</a>
                                	<ul class="nav nav-third-level collapse">
                                		<li><a href="{{ URL::to('importfacilitydata')}}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.import-facility-data', 3) }}</a></li>
                                		<li><a href="{{ URL::to('importtestkit')}}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.import-test-kit', 3) }}</a></li>
                                		<li><a href="{{ URL::to('testkit')}}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.test-kit', 3) }}</a></li>
                                        <li><a href="{{ URL::to('agency')}}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.agency', 3) }}</a></li>
                                	</ul>
                                </li>
                            </ul>
                            <ul class="nav nav-second-level collapse">
                                 <li><a href=""><i class="fa fa-tag"></i> {{ Lang::choice('messages.management', 2) }}</a>
                            <ul class="nav nav-third-level collapse">
                            <li><a href=""><i class="fa fa-tag"></i> {{ Lang::choice('messages.facility-management', 3) }}</a>
                               	<ul class="nav nav-fourth-level collapse">
                                <li><a href="{{ URL::to('facility')}}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.facility', 4) }}</a></li>
                                <li><a href="{{ URL::to('facilityType')}}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.facility-type', 4) }} </a></li>
                                <li><a href="{{ URL::to('facilityOwner')}}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.facility-owner', 4) }} </a></li>
                                <li><a href="{{ URL::to('county')}}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.county', 4) }} </a></li>
                                <li><a href="{{ URL::to('constituency')}}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.constituency', 4) }} </a></li>
                               
                            </ul>
							</li>
						</ul>
								<ul class="nav nav-third-level collapse">
                                <li><a href=""><i class="fa fa-tag"></i> {{ Lang::choice('messages.site-management', 3) }}</a>
                                <ul class="nav nav-fourth-level collapse">
                                <li><a href="{{ URL::to('site')}}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.site', 4) }}</a></li>
                                <li><a href="{{ URL::to('siteType')}}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.site-type', 4) }} </a></li>
                                </ul>
                                </li>
                             </ul>


                                    <ul class="nav nav-third-level collapse">
                                     <li><a href=""><i class="fa fa-tag"></i> {{ Lang::choice('messages.test-kit-management', 3) }}</a>
                                	<ul class="nav nav-fourth-level collapse">
                                <li><a href="{{ URL::to('assigntestkit')}}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.assign-test-kit', 4) }}</a></li>
                                </ul>
                                </li>
                             </ul>
                                    <ul class="nav nav-third-level collapse">
                                    <li><a href=""><i class="fa fa-tag"></i> {{ Lang::choice('messages.result-management', 3) }}</a>
                                	<ul class="nav nav-fourth-level collapse">
                                <li><a href="{{ URL::to('result')}}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.results', 4) }}</a></li>
                                
                                </ul>
                                </li>
                             </ul>	
                                </li>
                             </ul>  
                              <ul class="nav nav-second-level collapse">
                                 <li><a href=""><i class="fa fa-tag"></i> {{ Lang::choice('messages.data-entry', 2) }}</a>
                                	<ul class="nav nav-third-level collapse">
                                		<li><a href="{{ URL::to('serial')}}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.page-summary-serial', 2) }}</a></li>
                                		<li><a href="{{ URL::to('parallel')}}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.page-summary-parallel', 2) }}</a></li>
                                		</ul>	
                                </li>
                             </ul> 
                             <ul class="nav nav-second-level collapse">
                                 <li><a href=""><i class="fa fa-tag"></i> {{ Lang::choice('messages.report', 2) }}</a>
                                	<ul class="nav nav-third-level collapse">
                                		<li><a href="{{ URL::to('logbookdata')}}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.logbook-data', 3) }}</a></li>
                                		<li><a href="{{ URL::to('trendreport')}}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.trend-report', 3) }}</a></li>
                                		<li><a href="{{ URL::to('testkituse')}}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.testkit-use', 3) }}</a></li>
                                		<li><a href="{{ URL::to('invalidresults')}}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.invalid-results', 3) }}</a></li>
                                		<li><a href="{{ URL::to('customreport')}}"><i class="fa fa-tag"></i> {{ Lang::choice('messages.custom-report', 3) }}</a></li>
                                	</ul>	
                                </li>
                             </ul>   
                            <a href="#"><i class="fa fa-files-o fa-fw"></i> {{ Lang::choice('messages.spi-rt', 1) }}<span class="fa arrow"></span></a>
                            <a href="#"><i class="fa fa-files-o fa-fw"></i> {{ Lang::choice('messages.m&e', 1) }}<span class="fa arrow"></span></a>
                          

                        </li>
                       
                        
                    </ul>






@show
