<!doctype html>

<html lang="en">

<head>

    <meta charset="utf-8"/>

    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>

    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>

    <title>NDC - Inventory Systems.</title>

    <link href="/dist/css/tabler.css" rel="stylesheet"/>

    <link href="/dist/css/tabler-flags.min.css" rel="stylesheet"/>

    <link href="/dist/css/tabler-payments.min.css" rel="stylesheet"/>

    <link href="/dist/css/tabler-vendors.min.css" rel="stylesheet"/>

    <link href="/dist/css/demo.min.css" rel="stylesheet"/>

    <link href="/dist/css/custom.css" rel="stylesheet"/>



    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />



    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap5.min.css">



    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.1.0/css/buttons.dataTables.min.css">



    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">


<style>
  .dt-buttons .dt-button {
    padding:3px;
    margin:3px;
  }
.dt-buttons {margin-bottom:1rem;}
  #dataList_length{ text-align:right;margin-bottom:1rem;margin-top:-2rem;}
  .extra { width:100%; height:32px; margin-bottom: 5px;}
  .extra .dt-buttons {width:100%;}

  #loading {
  display: none!important;
  width: 100%;
  height: 100%;
  top: 0;
  left: 0;
  position: fixed;
  display: block;
  opacity: 0.7;
  background-color: #fff;
  z-index: 10000;
  text-align: center;
}

#loading-image {
  position: absolute;
  top: 50%;
  left: 50%;
  z-index: 10001;
}
</style>


    <script src="/dist/libs/jquery/dist/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/litepicker/dist/litepicker.js"></script>

    <script>



      function timeAgo(input) {



        if(!input|| input==""||input=="0000-00-00 00:00:00") return "";

      var date = input instanceof Date ? input : new Date(input);

      var formatter = new Intl.RelativeTimeFormat('en');

      var ranges = {

        //years: 3600 * 24 * 365,

        //months: 3600 * 24 * 30,

        //weeks: 3600 * 24 * 7,

        days: 3600 * 24,

        hours: 3600,

        minutes: 60,

        seconds: 1

      };

      var secondsElapsed = (date.getTime() - Date.now()) / 1000;

      for (var key in ranges) {

        if (ranges[key] < Math.abs(secondsElapsed)) {

          var delta = secondsElapsed / ranges[key];

          var d = Math.abs( Math.round(delta));

          var v = formatter.format(Math.round(delta), key);



          return  "<span class='"+ (  d>360?"cpurple":d>180?"cred":d>90?"corange":d>30?"cyellow":"clime") +"'>"+v+"</span>";

        }

      }

    }



    </script>



  </head>

  <body class="antialiased ">

  <div class="watermark">Service-X</div>

    <div class="wrapper">
      <div id="loading">
        <img id="loading-image" src="/static/loading.gif" alt="Loading..." />
      </div>
