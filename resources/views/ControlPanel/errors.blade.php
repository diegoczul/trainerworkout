 @extends('layouts.controlpanel')

@section('content')

<div class="container-fluid">

                <!-- Page Heading -->
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            Dashboard <small>Statistics Overview</small>
                        </h1>
                    </div>
                </div>
                <!-- /.row -->

                <div class="row">
                     <h2 style="float:left; display:inline">Errors</h2> <a href="/ControlPanel/errors/reset" class="btn btn-danger" style="float:Right; display:inline" type="button" value="reset">Reset</a>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                       
                        <pre>
                            {{ $contents }}
                        </pre>
                        
                    </div>
                    
                </div>
                <!-- /.row -->

                
                <!-- /.row -->

                
                <!-- /.row -->

                
                <!-- /.row -->

            </div>

@endsection

@section("scripts")

<script type="text/javascript">
  //setTimeout(function () { location.reload(true); }, 10000);
</script>


@endsection