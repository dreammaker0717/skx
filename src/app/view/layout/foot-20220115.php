  </div>
</div>

<div class="modal modal-blur fade" id="modal-danger" tabindex="-1" aria-hidden="true" style="display: none;z-index:20000">
      <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          <div class="modal-status bg-danger"></div>
          <div class="modal-body text-center py-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon mb-2 text-danger icon-lg" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M12 9v2m0 4v.01"></path><path d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75"></path></svg>
            <h3 class="text-header"></h3>
            <div class="text-muted"></div>
          </div>
          <div class="modal-footer">
            <div class="w-100">
              <div class="row">
                <div class="col"><a href="#" class="btn btn-white w-100 btn-danger-cancel" data-bs-dismiss="modal">
                    Cancel
                  </a></div>
                <div class="col"><a href="#" class="btn btn-danger w-100 btn-danger-ok" data-bs-dismiss="modal">
                    
                  </a></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

<div class="toast-container position-fixed p-3 top-0 end-0">
</div>


<!--<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.js"></script>-->
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap5.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>


<script src="/dist/libs/apexcharts/dist/apexcharts.min.js"></script>
<script src="/dist/libs/jqvmap/dist/jquery.vmap.min.js"></script>
<script src="/dist/libs/jqvmap/dist/maps/jquery.vmap.world.js"></script>
<script src="/dist/libs/selectize/dist/js/standalone/selectize.min.js"></script>
<script src="/dist/libs/flatpickr/dist/flatpickr.min.js"></script>
<script src="/dist/libs/flatpickr/dist/plugins/rangePlugin.js"></script>
<script src="/dist/libs/nouislider/distribute/nouislider.min.js"></script>
<!-- Tabler Core -->
<script src="/dist/js/tabler.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js" integrity="sha512-UdIMMlVx0HEynClOIFSyOrPggomfhBKJE28LKl8yR3ghkgugPnG6iLfRfHwushZl1MOPSY6TsuBDGPK2X4zYKg==" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script src="/dist/js/number-flip.js"></script>
<div id="overlay"></div>
<?php
if(dict('is.no.page')===TRUE) {
?>
    <script>
        var element = document.getElementsByTagName("body");
         element[0].classList.add("border-top-wide");        
         element[0].classList.add("border-primary");
         element[0].classList.add("flex-column");
         element[0].classList.add("d-flex");        
    </script>
<?php
}
?>
<script>
	function failSound() {
		let src = '/sounds/fail.wav';
		let audio = new Audio(src);
		audio.play();
	}

	function successSound() {
		let src = '/sounds/success.wav';
		let audio = new Audio(src);
		audio.play();
	}
	function ohnoSound() {
		let src = '/sounds/OHNO.wav';
		let audio = new Audio(src);
		audio.play();
	}


function new_toast(typ, message) {
  var toaster = `<div class="toast d-flex align-items-center text-white bg-${typ} border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="toast-body">
      ${message}
    </div>
    <button type="button" class="btn-close btn-close-white ms-auto me-2" data-bs-dismiss="toast" aria-label="Close"></button>
  </div>`;
  var $toaster = $(toaster);
  $toaster.appendTo($('.toast-container'));
  $toaster.on("hidden.bs.toast",function(){  $(this).remove(); });
  $toaster.toast("show");
}







function areyousure(title, message, confirmText, cb) {
    $('#modal-danger').find('.text-header').html(title);
    $('#modal-danger').find('.text-muted').html(message);
    $('#modal-danger').find('.btn-danger-ok').html(confirmText).unbind("click").bind("click",cb);
    $('#modal-danger').modal("show");
}

 $("#images").on('click','span.image-zoomable', function(e) {
     if($(e.target).is(".image-zoomable")) {
        var x = $(this).css("backgroundImage");             
        $('#overlay')
            .css('backgroundImage', x)
            .addClass('open')
            .one('click', function() { $(this).removeClass('open'); });
     }
});

$(function(){
/*
  $('a[href="'+location.pathname+'"]').addClass("active");
  try
  {
    $('a[href="'+location.pathname+'"]').parents("li").find(">a").dropdown("toggle","show");
  }
  catch
  {

  }

  if(new URLSearchParams(window.location.search).get("s") != null) {
    $('a[href="/stocks/'+new URLSearchParams(window.location.search).get("s")+'"]').addClass("active");
    try
    {
      $('a[href="/stocks/'+new URLSearchParams(window.location.search).get("s")+'"]').parents("li").find(">a").dropdown("toggle","show");
    }
    catch
    {

    }
  }
*/

$(document).on('click.bs.dropdown.data-api', '.keep-open', function(e) {
   e.stopPropagation();
});

var cp = $('a[href="'+location.pathname+'"]').parent();
for(var i=0;i<10;i++) {
    cp = cp.parent();
    if(cp.is(".accordion-item")) { var cc = cp.find("button").first(); console.log("click2",cc); cc.trigger("click"); }
    if(cp.is("#navbar-menu"))break;
}
var cp = $('a[href="'+location.pathname+'"]').parent();
for(var i=0;i<10;i++) {
    cp = cp.parent();
    if(cp.is(".dropdown")) { var cc = cp.first().find("a").first(); console.log("click1",cc); cc.dropdown("toggle"); cc.addClass("keep-open");  }
    if(cp.is("#navbar-menu"))break;
}
$('a[href="'+location.pathname+'"]').addClass("active");
});

</script>


<!-- Google Fonts -->
<link
  href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
  rel="stylesheet"
/>

</body>
</html>