<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name = "viewport" content="initial-scale=1.0, user-scalable = no, width = device-width">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin - Bootstrap Admin Template</title>
    {{ HTML::style(asset('assets/css/ControlPanel/bootstrap.min.css')) }}
    {{ HTML::style(asset('assets/css/ControlPanel/sb-admin-2.css')) }}
    {{ HTML::style(asset('assets/fw/font-awesome-4.1.0/css/font-awesome.min.css')) }}
    {{ HTML::style(asset('assets/fw/datatables/media/css/jquery.dataTables.css')) }}
    {{ HTML::style(asset('assets/fw/metisMenu/dist/metisMenu.min.css')) }}


    {{ HTML::style(asset('assets/fw/jquery-ui-1.11.1.custom/jquery-ui.min.css')) }}
    {{ HTML::style(asset('assets/fw/fancybox/source/jquery.fancybox.css?v=2.1.5')) }}
    {{ HTML::style(asset('assets/fw/lightbox/css/lightbox.css')) }}
    {{ HTML::style(asset('assets/fw/chosen_v1/docsupport/prism.css')) }}
    {{ HTML::style(asset('assets/fw/chosen_v1/chosen.css')) }}

    {{ HTML::style(asset('assets/fw/fancybox/source/jquery.fancybox.css')) }}
    <!-- jQuery Version 1.11.0 -->
    {{ HTML::script(asset('assets/js/jquery-1.11.0.js')) }}
    {{ HTML::script(asset('assets/js/jquery-ui.js')) }}


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body>
<div class="systemMessages"></div>
<div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Trainer Workout Control Panel LANGUANGE: {{ App::getLocale() }}</a>
            </div>
            <!-- /.navbar-header -->

            
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                <button class="btn btn-default" type="button">
                                    <i class="fa fa-search"></i>
                                </button>
                            </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        <li>
                            <a href="/ControlPanel/"><i class="fa fa-dashboard fa-fw"></i> Users</a>
                        </li>
                        <li>
                            <a href="/ControlPanel/Groups"><i class="fa fa-edit fa-fw"></i> Groups</a>
                        </li>
                        <li>
                            <a href="/ControlPanel/Exercises"><i class="fa fa-table fa-fw"></i> Exercises</a>
                        </li>
                        <li>
                            <a href="/ControlPanel/BodyGroups"><i class="fa fa-table fa-fw"></i> BodyGroups</a>
                        </li>
                        <li>
                            <a href="/ControlPanel/ExercisesTypes"><i class="fa fa-table fa-fw"></i> Exercises Types</a>
                        </li>
                        <li>
                            <a href="/ControlPanel/Workouts"><i class="fa fa-edit fa-fw"></i> Workouts</a>
                        </li>
                        <li>
                            <a href="/ControlPanel/Equipments"><i class="fa fa-edit fa-fw"></i> Equipments</a>
                        </li>
                        <li>
                            <a href="/ControlPanel/Ratings"><i class="fa fa-edit fa-fw"></i> Ratings</a>
                        </li>
                        <li>
                            <a href="/ControlPanel/Memberships"><i class="fa fa-edit fa-fw"></i> Memberships</a>
                        </li>
                        <li>
                            <a href="/ControlPanel/MembershipsTypes"><i class="fa fa-edit fa-fw"></i> Memberships Types</a>
                        </li>
                        <li>
                            <a href="/ControlPanel/UserLogos"><i class="fa fa-edit fa-fw"></i> White Label Logos</a>
                        </li>
                        <li>
                            <a href="/ControlPanel/MaintenanceScripts"><i class="fa fa-edit fa-fw"></i> Maintenance Scripts</a>
                        </li>
                        <li>
                            <a href="/ControlPanel/errors"><i class="fa fa-edit fa-fw"></i> Errors</a>
                        </li>
                        <li><a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Languages<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li><a href="/lang/en">English</a></li>
                                <li><a href="/lang/fr">French</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            @yield("content")
        </div>
        <!-- /#page-wrapper -->

    </div>

    {{ HTML::script(asset('assets/js/ControlPanel/bootstrap.min.js')) }}
    {{ HTML::script(asset('assets/js/ControlPanel/global.js')) }}
    {{ HTML::script(asset('assets/fw/datatables/media/js/jquery.dataTables.js')) }}
    {{ HTML::script(asset('assets/fw/metisMenu/dist/metisMenu.min.js')) }}
    {{ HTML::script(asset('assets/fw/ckeditor/ckeditor.js')) }}
    {{ HTML::script(asset('assets/fw/fancybox/source/jquery.fancybox.pack.js')) }}
    {{ HTML::script(asset('assets/fw/chosen_v1/chosen.jquery.js')) }}
    {{ HTML::script(asset('assets/fw/chosen_v1/docsupport/prism.js')) }}

    <script type="text/javascript">
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"},
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }


  </script>

    @yield("scripts")

</body>

</html>
