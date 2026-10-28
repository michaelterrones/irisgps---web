{{--*/
    if(isset($pageTitle)){
        $pageTitleSEO =  'IrisGPS - '.$pageTitle;
    }else{
        $pageTitle = 'Título de la página';
        $pageTitleSEO = 'IrisGPS'; 
    }
/*--}}
<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{$pageTitleSEO}}</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('vendor/sb-admin-2/bower_components/bootstrap/dist/css/bootstrap.min.css')}}" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="{{ asset('vendor/sb-admin-2/bower_components/metisMenu/dist/metisMenu.min.css')}}" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="https://cdn.datatables.net/responsive/1.0.6/css/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('vendor/sb-admin-2/dist/css/sb-admin-2.css')}}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{ asset('vendor/sb-admin-2/bower_components/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <!-- Roboto Font -->
    <link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>

    <!-- DualListBox CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/duallistbox/src/bootstrap-duallistbox.css') }}">

    <!-- Bootstrap-Datetime CSS -->
    <link href="{{ asset('assets/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">

    <!-- Custom IrisGPS CSS -->
    <link href="{{ asset('assets/css/irisgps.css')}}" rel="stylesheet">

    <!-- Custom IrisGPS CSS -->
    <link rel="shortcut icon" type="image/png" href="{{ asset('assets/images/favicon_indigo.png') }}"/>

    @yield('header')
</head>

<body class="{{(Request::is('login')) ? 'green-background' : ''}}">

    @yield('body')

    <!-- jQuery -->
    <script src="{{ asset('vendor/sb-admin-2/bower_components/jquery/dist/jquery.min.js') }}"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset('vendor/sb-admin-2/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="{{ asset('vendor/sb-admin-2/bower_components/metisMenu/dist/metisMenu.min.js') }}"></script>

    <!-- DataTables JavaScript -->
    <script src="https://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/1.0.6/js/dataTables.responsive.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="{{ asset('vendor/sb-admin-2/dist/js/sb-admin-2.js') }}"></script>

    <!-- DualListBox JavaScript -->
    <script src="{{ asset('vendor/duallistbox/dist/jquery.bootstrap-duallistbox.min.js') }}"></script>

    <!-- Bootstrap-Datetime Picker JavaScript -->
    <script src="{{ asset('assets/js/bootstrap-datetimepicker/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ asset('assets/js/bootstrap-datetimepicker/es.js') }}"></script>

    <!-- ConfirmJS -->
    <script src="{{asset('assets/js/confirmjs/jquery.confirm.js')}}"></script>
    <script src="{{asset('assets/js/confirmjs/confirm.init.js')}}"></script>

    <!-- HighCharts -->
    <script src="{{asset('assets/js/highcharts/highcharts.js')}}"></script>

    @yield('js')

</body>

</html>
