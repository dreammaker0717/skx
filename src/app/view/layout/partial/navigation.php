<?php $MODAL = @$_GET["MODAL"]; ?>
<aside class="navbar navbar-vertical navbar-expand-lg navbar-dark">
   <div class="container-fluid">
   <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"> <span class="navbar-toggler-icon"></span> </button>
   <!-------------------------- Logo -------------------------- -->
   <h1 class="navbar-brand navbar-brand-autodark"> <a href="/"> <img src="/static/logo.jpg" width="110" height="32" alt="Tabler" class="navbar-brand-image"> </a> </h1>
   <div class="collapse navbar-collapse" id="navbar-menu">
      <!-------------------------- Search Bar -------------------------- -->
      <div class="sidebar-search">
         <div>
            <div class="input-group">
               <input type="text" class="form-control search-menu" placeholder="Search..." onkeyup="GoSearch(event)">
               <div class="input-group-append">
                  <button type=button class="btn" onClick="GoSearch(event)" style="background-color:#8792a0;width: 34px;height:34px;padding:4px;">
                     <svg id="search-icon" class="search-icon" viewBox="0 0 24 24">
                        <path fill="palevioletred" d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
                        <path d="M0 0h24v24H0z" fill="none"/>
                     </svg>
                  </button>
               </div>
            </div>
         </div>
      </div>
      <div class="accordion" id="accordionPanelsStayOpenExample">
		  <!------------------------------- Stock Accordian  --------------------------------- -->
          <div class="accordion-item">
            <h2 class="accordion-header" id="panelsStayOpen-headingTen">
               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTen" aria-expanded="false" aria-controls="panelsStayOpen-collapseTen">
				<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
				   <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
				   <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" />
				   <line x1="12" y1="12" x2="20" y2="7.5" />
				   <line x1="12" y1="12" x2="12" y2="21" />
				   <line x1="12" y1="12" x2="4" y2="7.5" />
				   <line x1="16" y1="5.25" x2="8" y2="9.75" />
				</svg>
                  &nbsp;Stock
               </button>
            </h2>
            <div id="panelsStayOpen-collapseTen" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTen">
               <div class="accordion-body">
                  <ul class="navbar-nav pt-lg-3">

                     <!------------------------------- All Stock  --------------------------------- -->
                     <li class="nav-item">
                        <a class="nav-link" href="/rfqstocks" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
								<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
								   <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
								   <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" />
								   <line x1="12" y1="12" x2="20" y2="7.5" />
								   <line x1="12" y1="12" x2="12" y2="21" />
								   <line x1="12" y1="12" x2="4" y2="7.5" />
								   <line x1="16" y1="5.25" x2="8" y2="9.75" />
								</svg>
                           </span>
                           <span class="nav-link-title">&nbsp;All Stock</span>
                        </a>
                     </li>
                     <!------------------------------- Low Stock  --------------------------------- -->					  
                     <li class="nav-item">
                        <a class="nav-link" href="/rfqstockslow" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
								<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
									viewBox="0 0 64 64" enable-background="new 0 0 64 64" xml:space="preserve">
								<path fill="currentColor" d="M46,34c-0.552,0-1-0.447-1-1V0h14v20c0,0.553-0.448,1-1,1s-1-0.447-1-1V2H47v31C47,33.553,46.552,34,46,34z"/>
								<path fill="currentColor" d="M59,64H45V46c0-0.553,0.448-1,1-1s1,0.447,1,1v16h10V34c0-0.553,0.448-1,1-1s1,0.447,1,1V64z"/>
								<path fill="currentColor" d="M38,37c-0.552,0-1-0.447-1-1V17H27v13c0,0.553-0.448,1-1,1s-1-0.447-1-1V15h14v21C39,36.553,38.552,37,38,37z"/>
								<path fill="currentColor" d="M39,64H25V41c0-0.553,0.448-1,1-1s1,0.447,1,1v21h10V47c0-0.553,0.448-1,1-1s1,0.447,1,1V64z"/>
								<path fill="currentColor" d="M6,42c-0.552,0-1-0.447-1-1V26h14v6c0,0.553-0.448,1-1,1s-1-0.447-1-1v-4H7v13C7,41.553,6.552,42,6,42z"/>
								<path fill="currentColor" d="M19,64H5V53c0-0.553,0.448-1,1-1s1,0.447,1,1v9h10V44c0-0.553,0.448-1,1-1s1,0.447,1,1V64z"/>
								<path fill="currentColor" d="M2.001,52c-0.603,0-1.199-0.271-1.592-0.788c-0.669-0.879-0.5-2.134,0.379-2.803l22.015-16.773l18.759,9.873
											L60.52,20.654c0.743-0.816,2.008-0.877,2.825-0.134c0.817,0.742,0.877,2.008,0.135,2.825L42.438,46.491L23.197,36.364
											L3.212,51.591C2.85,51.866,2.424,52,2.001,52z"/>
								</svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Low Stock</span>
                        </a>
                     </li>					  
                     <!------------------------------- QTY Difference  --------------------------------- -->					  
                     <li class="nav-item">
                        <a class="nav-link" href="/rfqstocksqty" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
								  <path fill="currentColor" d="M13,2 C14.5976809,2 15.9036609,3.24891996 15.9949073,4.82372721 L16,5 L16,8 L19,8 C20.5976809,8 21.9036609,9.24891996 21.9949073,10.8237272 L22,11 L22,19 C22,20.5976809 20.75108,21.9036609 19.1762728,21.9949073 L19,22 L11,22 C9.40231912,22 8.09633912,20.75108 8.00509269,19.1762728 L8,19 L8,16 L5,16 C3.40231912,16 2.09633912,14.75108 2.00509269,13.1762728 L2,13 L2,5 C2,3.40231912 3.24891996,2.09633912 4.82372721,2.00509269 L5,2 L13,2 Z M19,10 L16,10 L16,13 C16,14.5976809 14.75108,15.9036609 13.1762728,15.9949073 L13,16 L10,16 L10,19 C10,19.5128358 10.3860402,19.9355072 10.8833789,19.9932723 L11,20 L19,20 C19.5128358,20 19.9355072,19.6139598 19.9932723,19.1166211 L20,19 L20,11 C20,10.4871642 19.6139598,10.0644928 19.1166211,10.0067277 L19,10 Z M13,4 L5,4 C4.48716416,4 4.06449284,4.38604019 4.00672773,4.88337887 L4,5 L4,13 C4,13.5128358 4.38604019,13.9355072 4.88337887,13.9932723 L5,14 L8,14 L8,11 C8,9.40231912 9.24891996,8.09633912 10.8237272,8.00509269 L11,8 L14,8 L14,5 C14,4.48716416 13.6139598,4.06449284 13.1166211,4.00672773 L13,4 Z"/>
								</svg>
						   </span>
                           <span class="nav-link-title">&nbsp;QTY Differences</span>
                        </a>
                     </li>						  
                      <!------------------------------- Out of Stock  --------------------------------- -->					  
                     <li class="nav-item">
                        <a class="nav-link" href="/rfqstocksoos" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
								<svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" height="24" width="24">
								<path style="fill:currentColor;fill-opacity:1;stroke:none" d="M 2 2 L 2 7 L 9 7 L 9 2 L 2 2 z M 10 2 L 10 14 L 11 14 L 11 2 L 10 2 z M 3 3 L 8 3 L 8 6 L 3 6 L 3 3 z M 4 9 L 4 14 L 9 14 L 9 9 L 4 9 z M 12 9 L 12 14 L 14 14 L 14 9 L 12 9 z M 5 10 L 8 10 L 8 13 L 5 13 L 5 10 z "/>
								</svg>
						   </span>
                           <span class="nav-link-title">&nbsp;Out of Stock</span>
                        </a>
                     </li>	
					  <!------------------------------- Disabled Stock --------------------------------- -->					  
                     <li class="nav-item">
                        <a class="nav-link" href="/rfqstocksdisabled" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="24" height="24" viewBox="0 0 256 256" xml:space="preserve">
								   <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)" >
									  <path d="M 83.891 81.357 H 6.11 c -3.369 0 -6.11 -2.741 -6.11 -6.11 V 23.821 c 0 -3.369 2.741 -6.109 6.11 -6.109 h 2.665 c 0.846 0 1.534 -0.688 1.534 -1.534 c 0 -4.155 3.38 -7.535 7.535 -7.535 h 21.969 c 2.821 0 5.382 1.555 6.683 4.057 l 2.178 4.186 c 0.266 0.509 0.787 0.826 1.361 0.826 h 33.855 c 3.369 0 6.109 2.741 6.109 6.109 v 51.426 C 90 78.616 87.26 81.357 83.891 81.357 z M 6.11 23.711 C 6.049 23.711 6 23.76 6 23.821 v 51.426 c 0 0.061 0.049 0.11 0.11 0.11 h 77.781 c 0.061 0 0.109 -0.05 0.109 -0.11 V 23.821 c 0 -0.061 -0.049 -0.109 -0.109 -0.109 H 50.035 c -2.819 0 -5.38 -1.554 -6.683 -4.056 l -2.179 -4.187 c -0.265 -0.51 -0.786 -0.826 -1.36 -0.826 H 17.844 c -0.846 0 -1.535 0.688 -1.535 1.535 c 0 4.154 -3.38 7.534 -7.534 7.534 H 6.11 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: currentColor; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
									  <path d="M 28.858 58.915 H 13.395 c -1.657 0 -3 -1.343 -3 -3 s 1.343 -3 3 -3 h 15.463 c 1.657 0 3 1.343 3 3 S 30.515 58.915 28.858 58.915 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: currentColor; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
									  <path d="M 40.436 69.839 H 13.395 c -1.657 0 -3 -1.343 -3 -3 s 1.343 -3 3 -3 h 27.041 c 1.657 0 3 1.343 3 3 S 42.092 69.839 40.436 69.839 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: currentColor; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
								   </g>
								</svg>
						   </span>
                           <span class="nav-link-title">&nbsp;Disabled Stock</span>
                        </a>
                     </li>							  
					  <!------------------------------- Ignored QTY Differences --------------------------------- -->					  
                     <li class="nav-item">
                        <a class="nav-link" href="/rfqstocksqtyignored" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
								   <g id="surface1">
								   <path style=" stroke:none;fill-rule:evenodd;fill:currentColor;fill-opacity:1;" d="M 17.398438 4.015625 C 17.621094 3.550781 17.933594 3.316406 18.339844 3.316406 C 18.710938 3.316406 19 3.496094 19.199219 3.847656 C 19.398438 4.203125 19.5 4.683594 19.5 5.296875 C 19.5 5.960938 19.382812 6.492188 19.148438 6.898438 C 18.914062 7.300781 18.601562 7.5 18.207031 7.5 C 17.851562 7.5 17.582031 7.3125 17.402344 6.9375 L 17.390625 6.9375 L 17.390625 7.402344 L 16.5 7.402344 L 16.5 1.5 L 17.390625 1.5 L 17.390625 4.015625 L 17.402344 4.015625 Z M 17.375 5.664062 C 17.367188 5.890625 17.421875 6.109375 17.535156 6.304688 C 17.644531 6.472656 17.78125 6.558594 17.949219 6.558594 C 18.152344 6.558594 18.308594 6.449219 18.417969 6.234375 C 18.53125 6.015625 18.589844 5.710938 18.589844 5.3125 C 18.589844 4.980469 18.535156 4.726562 18.433594 4.539062 C 18.332031 4.355469 18.1875 4.261719 17.996094 4.261719 C 17.816406 4.261719 17.667969 4.351562 17.550781 4.539062 C 17.425781 4.757812 17.363281 5.003906 17.375 5.257812 Z M 6.179688 11.542969 L 3 8.351562 L 3.992188 7.359375 L 5.503906 8.859375 L 5.503906 6.59375 C 5.5 6.039062 5.71875 5.507812 6.109375 5.117188 C 6.5 4.722656 7.027344 4.5 7.582031 4.5 L 11.101562 4.5 L 11.101562 5.859375 L 7.582031 5.859375 C 7.1875 5.875 6.878906 6.203125 6.878906 6.597656 L 6.890625 8.847656 L 8.316406 7.429688 L 9.296875 8.414062 Z M 14.035156 7.394531 L 15 7.394531 L 15 4.828125 C 15 3.613281 14.527344 3 13.585938 3 C 13.382812 3 13.15625 3.035156 12.910156 3.109375 C 12.703125 3.164062 12.507812 3.25 12.328125 3.359375 L 12.328125 4.359375 C 12.683594 4.054688 13.058594 3.902344 13.453125 3.902344 C 13.84375 3.902344 14.039062 4.136719 14.039062 4.605469 L 13.140625 4.757812 C 12.382812 4.886719 12 5.367188 12 6.199219 C 12 6.59375 12.089844 6.910156 12.273438 7.148438 C 12.457031 7.382812 12.738281 7.511719 13.035156 7.5 C 13.46875 7.5 13.796875 7.261719 14.019531 6.78125 L 14.035156 6.78125 Z M 14.039062 5.363281 L 14.039062 5.652344 C 14.046875 5.882812 13.984375 6.113281 13.859375 6.304688 C 13.757812 6.464844 13.582031 6.5625 13.390625 6.5625 C 13.265625 6.566406 13.144531 6.515625 13.058594 6.417969 C 12.976562 6.3125 12.933594 6.179688 12.941406 6.046875 C 12.941406 5.714844 13.105469 5.519531 13.4375 5.464844 Z M 10.5 19.394531 L 9.535156 19.394531 L 9.535156 18.78125 L 9.519531 18.78125 C 9.296875 19.261719 8.96875 19.5 8.535156 19.5 C 8.238281 19.511719 7.957031 19.382812 7.773438 19.148438 C 7.589844 18.910156 7.5 18.59375 7.5 18.203125 C 7.5 17.367188 7.882812 16.886719 8.640625 16.757812 L 9.539062 16.605469 C 9.539062 16.136719 9.34375 15.898438 8.953125 15.898438 C 8.558594 15.898438 8.183594 16.054688 7.828125 16.359375 L 7.828125 15.359375 C 7.96875 15.265625 8.164062 15.179688 8.410156 15.109375 C 8.65625 15.035156 8.882812 15 9.085938 15 C 10.027344 15 10.5 15.609375 10.5 16.828125 Z M 9.539062 17.652344 L 9.539062 17.363281 L 8.941406 17.464844 C 8.605469 17.519531 8.441406 17.714844 8.441406 18.046875 C 8.441406 18.195312 8.480469 18.320312 8.558594 18.417969 C 8.644531 18.515625 8.765625 18.566406 8.890625 18.5625 C 9.078125 18.5625 9.257812 18.464844 9.359375 18.304688 C 9.476562 18.132812 9.539062 17.914062 9.539062 17.652344 Z M 13.894531 19.5 C 14.375 19.5 14.746094 19.414062 15 19.242188 L 15 18.175781 C 14.765625 18.367188 14.476562 18.476562 14.171875 18.484375 C 13.886719 18.5 13.609375 18.382812 13.421875 18.164062 C 13.242188 17.945312 13.152344 17.644531 13.152344 17.265625 C 13.152344 16.875 13.246094 16.570312 13.433594 16.347656 C 13.628906 16.125 13.914062 16 14.207031 16.015625 C 14.5 16.015625 14.761719 16.117188 15 16.324219 L 15 15.199219 C 14.804688 15.066406 14.492188 15 14.0625 15 C 13.433594 15 12.933594 15.210938 12.558594 15.632812 C 12.1875 16.054688 12 16.625 12 17.355469 C 12 17.984375 12.175781 18.5 12.523438 18.898438 C 12.871094 19.300781 13.328125 19.5 13.894531 19.5 Z M 3 13.5 L 4.5 12 L 18 12 L 19.5 13.5 L 19.5 21 L 18 22.5 L 4.5 22.5 L 3 21 Z M 4.5 13.5 L 4.5 21 L 18 21 L 18 13.5 Z M 9 10.5 L 10.5 9 L 21 9 L 22.5 10.5 L 22.5 18 L 21 19.5 L 21 10.5 Z M 9 10.5 "/>
								   </g>
								</svg>
						   </span>
                           <span class="nav-link-title">&nbsp;Ignored QTY Differences</span>
                        </a>
                     </li>						  
					  <!------------------------------- Is Assembled  --------------------------------- -->					  
                     <li class="nav-item">
                        <a class="nav-link" href="/rfqstocksisassembled" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
								<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 122.88 109.77"><path fill-rule="evenodd" fill="currentColor" d="M26.13,8.81A17.18,17.18,0,0,0,13.24,4.72c14,11.42-2.58,26.17-11.79,9-3.78,8.49.16,16.31,8,20.33,3.7,1.9,5.65,3,7,4.53a11.73,11.73,0,0,1,1.86,2.92l3.49,8.26H7.11A2.32,2.32,0,0,0,4.8,52.1V93a16.87,16.87,0,0,0,16.82,16.82H96.79A16.87,16.87,0,0,0,113.61,93V52.1a2.32,2.32,0,0,0-2.32-2.32H88.89l13.07-22-9.37-5.4L76.21,49.78H65.79l0-2.86a.57.57,0,0,0-.12-.35c-.5-.6-.88-1-1.2-1.41h0c-.74-.86-1.12-1.29-1.12-1.57s.36-.75,1.09-1.65c.3-.37.66-.81,1.08-1.36a.57.57,0,0,0,.13-.36V37.05a.55.55,0,0,0-.55-.55l-4.85,0,.09-22.09,2.92-5.11a2.85,2.85,0,0,0,.46-1.55l0-5A2.83,2.83,0,0,0,60.82,0L54,0a2.83,2.83,0,0,0-2.79,2.87l.08,5.54A2.79,2.79,0,0,0,51.81,10l2.73,3.91-.09,22.63-5.74,0a.55.55,0,0,0-.55.56v3.1a.54.54,0,0,0,.14.36c.47.52.87,1,1.22,1.32,1,1.07,1.48,1.59,1.49,2s-.41.86-1.19,1.85c-.39.49-.87,1.1-1.44,1.86a.5.5,0,0,0-.12.35v1.86H35.19l-5.5-12.91a12.84,12.84,0,0,1-1.11-3.79,14.19,14.19,0,0,1,.28-3.75c1.33-7.78,4-14.25-2.73-20.52ZM9.43,63.94H109V54.41H9.43v9.53ZM109,68.57H95.13v7.79A3.17,3.17,0,0,1,92,79.53H80.86a3.18,3.18,0,0,1-3.17-3.17V68.57H41.82v7.79a3.18,3.18,0,0,1-3.17,3.17H27.55a3.18,3.18,0,0,1-3.18-3.17V68.57H9.43V93a12.24,12.24,0,0,0,12.19,12.2H96.79A12.24,12.24,0,0,0,109,93V68.57ZM75.29,18.83a15.81,15.81,0,0,1,5.3-2.57,10.13,10.13,0,0,1,7.87.85l20.38,11.75L107.61,31l7.75,4.51,7.52-13L115.14,18l-1.4,2.36L93.48,8.68a10.31,10.31,0,0,0-8-1.38c-4.24,1.14-7.65,5-10.23,11.53Z"/></svg>
						   </span>
                           <span class="nav-link-title">&nbsp;Is Assembled</span>
                        </a>
                     </li>                  
                 <!------------------------------- Assembly Suggestions  --------------------------------- -->                 
                     <li class="nav-item">
                        <a class="nav-link" href="/assemblysuggestions/existing_allowed" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                        <svg width="24" height="24" viewBox="0 -18 1060 1060" fill="currentColor" class="icon"  version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M963.2 408c-13.6 0-24 11.2-24 24v432c0 27.2-21.6 48.8-48.8 48.8H159.2c-27.2 0-48.8-21.6-48.8-48.8V159.2c0-27.2 21.6-48.8 48.8-48.8h470.4c13.6 0 24-11.2 24-24 0-13.6-11.2-24-24-24H154.4C103.2 62.4 61.6 104 61.6 155.2v715.2c0 51.2 41.6 92.8 92.8 92.8h740.8c51.2 0 92.8-41.6 92.8-92.8V432c0-12.8-11.2-24-24.8-24z" fill="" /><path d="M968 151.2l-44-44c-30.4-30.4-78.4-33.6-105.6-5.6L355.2 564.8 510.4 720l463.2-463.2c28-27.2 25.6-75.2-5.6-105.6z m-116.8 159.2l-344 344-86.4-85.6 345.6-345.6 84.8 87.2z m88-88l-51.2 51.2-85.6-86.4 50.4-50.4c10.4-10.4 28.8-9.6 40 2.4l44 44c12 10.4 12.8 28.8 2.4 39.2zM355.2 566.4l-48 174.4c-2.4 8 0 16 5.6 21.6 5.6 5.6 13.6 8 21.6 5.6l174.4-48-36-36L360 715.2l31.2-113.6-36-35.2z" fill="" /></svg>
                     </span>
                           <span class="nav-link-title">&nbsp;Assembly Suggestions</span>
                        </a>
                     </li>
				   </ul>
				</div>
			  </div>
			  
			  
		  </div>
		  <!------------------------------- RFQ Accordian  --------------------------------- -->
         <div class="accordion-item">
            <h2 class="accordion-header" id="panelsStayOpen-headingNine">
               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseNine" aria-expanded="false" aria-controls="panelsStayOpen-collapseNine">
					<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-file-dollar" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
					   <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
					   <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
					   <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z"></path>
					   <path d="M14 11h-2.5a1.5 1.5 0 0 0 0 3h1a1.5 1.5 0 0 1 0 3h-2.5"></path>
					   <path d="M12 17v1m0 -8v1"></path>
					</svg>
                  &nbsp;RFQ
               </button>
            </h2>
            <div id="panelsStayOpen-collapseNine" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingNine">
               <div class="accordion-body">
                  <ul class="navbar-nav pt-lg-3">

                     <!------------------------------- RFQs  --------------------------------- -->
                     <li class="nav-item">
                        <a class="nav-link" href="/newitemrfqs" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-ballpen" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                 <path d="M14 6l7 7l-4 4"></path>
                                 <path d="M5.828 18.172a2.828 2.828 0 0 0 4 0l10.586 -10.586a2 2 0 0 0 0 -2.829l-1.171 -1.171a2 2 0 0 0 -2.829 0l-10.586 10.586a2.828 2.828 0 0 0 0 4z"></path>
                                 <path d="M4 20l1.768 -1.768"></path>
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;RFQ</span>
                        </a>
                     </li>


                    
                      <!------------------------------- Orders  --------------------------------- -->
					  <li class="nav-item">
                        <a class="nav-link" href="/rfqorders" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <line x1="9" y1="6" x2="20" y2="6" />
                                 <line x1="9" y1="12" x2="20" y2="12" />
                                 <line x1="9" y1="18" x2="20" y2="18" />
                                 <line x1="5" y1="6" x2="5" y2="6.01" />
                                 <line x1="5" y1="12" x2="5" y2="12.01" />
                                 <line x1="5" y1="18" x2="5" y2="18.01" />
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Orders</span>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="/rfqstocksonorder" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
                                    <path fill="currentColor" d="M59.072,0v98.464h98.464V0H59.072z M141.536,82.464H75.072V16h66.464V82.464z"/>
                                    <path fill="currentColor" d="M59.072,137.84v98.464h98.464V137.84H59.072z M141.536,220.304H75.072V153.84h66.464V220.304z"/>
                                    <path fill="currentColor" d="M59.072,275.696v98.464h98.464v-98.464H59.072z M141.536,358.16H75.072v-66.464h66.464V358.16z"/>
                                    <path fill="currentColor" d="M59.072,413.536V512h98.464v-98.464H59.072z M141.536,496H75.072v-66.464h66.464V496z"/>
                                    <rect fill="currentColor" x="196.928" y="21.536" width="216.608" height="16"/>
                                    <rect fill="currentColor" x="196.928" y="60.928" width="256" height="16"/>
                                    <rect fill="currentColor" x="196.928" y="159.392" width="216.608" height="16"/>
                                    <rect fill="currentColor" x="196.928" y="198.768" width="256" height="16"/>
                                    <rect fill="currentColor" x="196.928" y="297.232" width="216.608" height="16"/>
                                    <rect fill="currentColor" x="196.928" y="336.64" width="256" height="16"/>
                                    <rect fill="currentColor" x="196.928" y="435.04" width="216.608" height="16"/>
                                    <rect fill="currentColor" x="196.928" y="474.464" width="256" height="16"/>
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Products on Order</span>
                        </a>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
		  <!------------------------------  New Items Accordian -------------------------------------- -->
         <div class="accordion-item">
            <h2 class="accordion-header" id="panelsStayOpen-headingSeven">
               <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseSeven" aria-expanded="false" aria-controls="panelsStayOpen-collapseSeven">
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                     <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                     </svg>
                  </span>
                  New Stock
               </button>
            </h2>
            <div id="panelsStayOpen-collapseSeven" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingSeven">
               <div class="accordion-body">
                  <ul class="navbar-nav pt-lg-3">
                     <!------------------------------  New Items Orders  -------------------------------------- -->
                     <li class="nav-item">
                        <a class="nav-link" href="/newitemorders" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <line x1="9" y1="6" x2="20" y2="6" />
                                 <line x1="9" y1="12" x2="20" y2="12" />
                                 <line x1="9" y1="18" x2="20" y2="18" />
                                 <line x1="5" y1="6" x2="5" y2="6.01" />
                                 <line x1="5" y1="12" x2="5" y2="12.01" />
                                 <line x1="5" y1="18" x2="5" y2="18.01" />
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Orders</span>
                        </a>
                     </li>
                     <!------------------------------  New Items Stock  -------------------------------------- -->
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" role="button" aria-expanded="false" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <!-- Download SVG icon from http://tabler-icons.io/i/package -->
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" />
                                 <line x1="12" y1="12" x2="20" y2="7.5" />
                                 <line x1="12" y1="12" x2="12" y2="21" />
                                 <line x1="12" y1="12" x2="4" y2="7.5" />
                                 <line x1="16" y1="5.25" x2="8" y2="9.75" />
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Stock</span>
                        </a>
                        <div class="dropdown-menu">
                           <div class="dropdown-menu-columns newitemstock">
                              <a class="dropdown-item" href="/newitemstocks/darkgreen" > <span class="btn btn-primary btn-sm btn-teal me-1 newitems_status_7" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Dark Green - Ready To Sell">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/newitemstocks/sold" > <span class="btn btn-primary btn-sm btn-secondary me-1 newitems_status_16" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Sold Items">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/newitemstocks/orange" > <span class="btn btn-primary btn-sm btn-orange me-1 newitems_status_1"style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Orange - Returned By Customer">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/newitemstocks/brown" > <span class="btn btn-primary btn-sm btn-pinterest me-1 newitems_status_11"style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Out-of-House Work">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/newitemstocks/lightblue" > <span class="btn btn-primary btn-sm btn-cyan me-1 newitems_status_4"style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Used Internally">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/newitemstocks/red" > <span class="btn btn-primary btn-sm btn-red me-1 newitems_status_3"style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="FAULTY - Return to Supplier">0</span>&nbsp;</a>

                           </div>
                        </div>
                     </li>
                     <!------------------------------  New Items Products  -------------------------------------- -->
                     <li class="nav-item">
                        <a class="nav-link" href="/admin/newitemproducts" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alien" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <desc>Download more icon variants from https://tabler-icons.io/i/alien</desc>
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                 <path d="M11 17a2.5 2.5 0 0 0 2 0"></path>
                                 <path d="M12 3c-4.664 0 -7.396 2.331 -7.862 5.595a11.816 11.816 0 0 0 2 8.592a10.777 10.777 0 0 0 3.199 3.064c1.666 1 3.664 1 5.33 0a10.777 10.777 0 0 0 3.199 -3.064a11.89 11.89 0 0 0 2 -8.592c-.466 -3.265 -3.198 -5.595 -7.862 -5.595z"></path>
                                 <line x1="8" y1="11" x2="10" y2="13"></line>
                                 <line x1="16" y1="11" x2="14" y2="13"></line>
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Products</span>
                        </a>
                     </li>
                     <!------------------------------  New Items Non-Unique Products  -------------------------------------- -->
                     <li class="nav-item">
                        <a class="nav-link" href="/admin/newitemproducts2" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-assembly" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                 <path d="M19 6.873a2 2 0 0 1 1 1.747v6.536a2 2 0 0 1 -1.029 1.748l-6 3.833a2 2 0 0 1 -1.942 0l-6 -3.833a2 2 0 0 1 -1.029 -1.747v-6.537a2 2 0 0 1 1.029 -1.748l6 -3.572a2.056 2.056 0 0 1 2 0l6 3.573h-.029z"></path>
                                 <path d="M15.5 9.422c.312 .18 .503 .515 .5 .876v3.277c0 .364 -.197 .7 -.515 .877l-3 1.922a0.997 .997 0 0 1 -.97 0l-3 -1.922a1.003 1.003 0 0 1 -.515 -.876v-3.278c0 -.364 .197 -.7 .514 -.877l3 -1.79c.311 -.174 .69 -.174 1 0l3 1.79h-.014z"></path>
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Products (Non-Unique)</span>
                        </a>
                     </li>
                     <!------------------------------  New Items Admin  -------------------------------------- -->
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" role="button" aria-expanded="false" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                                 <circle cx="12" cy="12" r="3" />
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Admin</span>
                        </a>
                        <div class="dropdown-menu">
                           <a class="dropdown-item" href="/newitemserial" > Change Serial Number </a>
                           <a class="dropdown-item" href="/tool2/newitem_loader" > Database Management </a>
                        </div>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
         <!------------------------------  Accesories Accordian -------------------------------------- -->
         <div class="accordion-item">
            <h2 class="accordion-header" id="panelsStayOpen-headingOne">
               <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="false" aria-controls="panelsStayOpen-collapseOne">
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                     <!-- Download SVG icon from http://tabler-icons.io/i/keyboard -->
                     <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-keyboard" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <rect x="2" y="6" width="20" height="12" rx="2"></rect>
                        <line x1="6" y1="10" x2="6" y2="10"></line>
                        <line x1="10" y1="10" x2="10" y2="10"></line>
                        <line x1="14" y1="10" x2="14" y2="10"></line>
                        <line x1="18" y1="10" x2="18" y2="10"></line>
                        <line x1="6" y1="14" x2="6" y2="14.01"></line>
                        <line x1="18" y1="14" x2="18" y2="14.01"></line>
                        <line x1="10" y1="14" x2="14" y2="14"></line>
                     </svg>
                  </span>
                  Accessories
               </button>
            </h2>
            <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingOne">
               <div class="accordion-body">
                  <ul class="navbar-nav pt-lg-3">
                     <!------------------------------  Accesories Orders  -------------------------------------- -->
                     <li class="nav-item">
                        <a class="nav-link" href="/accorders" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <!-- Download SVG icon from http://tabler-icons.io/i/list -->
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <line x1="9" y1="6" x2="20" y2="6" />
                                 <line x1="9" y1="12" x2="20" y2="12" />
                                 <line x1="9" y1="18" x2="20" y2="18" />
                                 <line x1="5" y1="6" x2="5" y2="6.01" />
                                 <line x1="5" y1="12" x2="5" y2="12.01" />
                                 <line x1="5" y1="18" x2="5" y2="18.01" />
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Orders</span>
                        </a>
                     </li>
                     <!------------------------------  Accesories Stock  -------------------------------------- -->
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" role="button" aria-expanded="false" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <!-- Download SVG icon from http://tabler-icons.io/i/package -->
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" />
                                 <line x1="12" y1="12" x2="20" y2="7.5" />
                                 <line x1="12" y1="12" x2="12" y2="21" />
                                 <line x1="12" y1="12" x2="4" y2="7.5" />
                                 <line x1="16" y1="5.25" x2="8" y2="9.75" />
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Stock</span>
                        </a>
                        <div class="dropdown-menu">
                           <div class="dropdown-menu-columns accstock">
                              <a class="dropdown-item" href="/accstocks/orange" > <span class="btn btn-primary btn-sm btn-orange me-1 ast_status_1"style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Orange - Awaiting Diagnosis">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/accstocks/purple" > <span class="btn btn-primary btn-sm btn-purple me-1 ast_status_2" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Purple - Awaiting Repair">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/accstocks/red" > <span class="btn btn-primary btn-sm btn-red me-1 ast_status_3" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Red - Faulty Needing Parts">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/accstocks/lightblue" > <span class="btn btn-primary btn-sm btn-cyan me-1 ast_status_4" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Light Blue - Parts Ordered">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/accstocks/usedinternal" > <span class="btn btn-primary btn-sm btn-cyan me-1 ast_status_30"style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Used Internally">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/accstocks/lightgreen" > <span class="btn btn-primary btn-sm btn-lime me-1 ast_status_6" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Light Green - Ready To Sell (Grade B)">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/accstocks/green" > <span class="btn btn-primary btn-sm btn-green me-1 ast_status_22" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Green - Ready To Sell (Grade A)">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/accstocks/darkgreen" > <span class="btn btn-primary btn-sm btn-teal me-1 ast_status_7" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Dark Green - Ready To Sell (New)">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/accstocks/sold" > <span class="btn btn-primary btn-sm btn-secondary me-1 ast_status_16" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Sold Items">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/accstocks/senttofba" > <span class="btn btn-primary btn-sm btn-secondary me-1 ast_status_29" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Sent to FBA">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/accstocks/black" > <span class="btn btn-primary btn-sm btn-dark me-1 ast_status_8" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Written Off Items">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/accstocks/rts" > <span class="btn btn-primary btn-sm btn-red me-1 ast_status_43" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Return To Supplier (Not Working Yet)">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/accstocks/rfs" > <span class="btn btn-primary btn-sm btn-dark me-1 ast_status_44" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Refunded By Supplier (Not Working Yet)">0</span>&nbsp;</a>
                           </div>
                        </div>
                     </li>
                     <!------------------------------  Accessories Products  -------------------------------------- -->
                     <li class="nav-item">
                        <a class="nav-link" href="/admin/aproducts" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alien" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <desc>Download more icon variants from https://tabler-icons.io/i/alien</desc>
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                 <path d="M11 17a2.5 2.5 0 0 0 2 0"></path>
                                 <path d="M12 3c-4.664 0 -7.396 2.331 -7.862 5.595a11.816 11.816 0 0 0 2 8.592a10.777 10.777 0 0 0 3.199 3.064c1.666 1 3.664 1 5.33 0a10.777 10.777 0 0 0 3.199 -3.064a11.89 11.89 0 0 0 2 -8.592c-.466 -3.265 -3.198 -5.595 -7.862 -5.595z"></path>
                                 <line x1="8" y1="11" x2="10" y2="13"></line>
                                 <line x1="16" y1="11" x2="14" y2="13"></line>
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Products</span>
                        </a>
                     </li>
                     <!------------------------------  Accesories Admin  -------------------------------------- -->
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" role="button" aria-expanded="false" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <!-- Download SVG icon from http://tabler-icons.io/i/settings -->
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                                 <circle cx="12" cy="12" r="3" />
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Admin</span>
                        </a>
                        <div class="dropdown-menu">
                           <a class="dropdown-item" href="/aserial" > Change Serial Number </a>
                           <a class="dropdown-item" href="/admin/accproductmap" > Product Map </a>
                           <a class="dropdown-item" href="/tool2/acc_loader" > Database Management </a>
                        </div>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
         <!------------------------------  Laptops Accordian -------------------------------------- -->
         <div class="accordion-item">
            <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                  <!-- Download SVG icon from http://tabler-icons.io/i/device-laptop -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                     <line x1="3" y1="19" x2="21" y2="19" />
                     <rect x="5" y="6" width="14" height="10" rx="1" />
                  </svg>
                  &nbsp;Laptops
               </button>
            </h2>
            <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
               <div class="accordion-body">
                  <ul class="navbar-nav pt-lg-3">
                     <!------------------------------- Laptop Orders --------------------------------- -->
                     <li class="nav-item">
                        <a class="nav-link" href="/orders" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <!-- Download SVG icon from http://tabler-icons.io/i/list -->
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <line x1="9" y1="6" x2="20" y2="6" />
                                 <line x1="9" y1="12" x2="20" y2="12" />
                                 <line x1="9" y1="18" x2="20" y2="18" />
                                 <line x1="5" y1="6" x2="5" y2="6.01" />
                                 <line x1="5" y1="12" x2="5" y2="12.01" />
                                 <line x1="5" y1="18" x2="5" y2="18.01" />
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Orders</span>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="/ebayorders" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <!-- Download SVG icon from http://tabler-icons.io/i/list -->
                              <svg width="24" height="24" viewBox="0 0 192 192" xmlns="http://www.w3.org/2000/svg" fill="none">
                                 <g stroke="currentColor" stroke-width="12" clip-path="url(#a)">
                                 <ellipse cx="96" cy="96" rx="78" ry="58" transform="rotate(-30 96 96)"/>
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M65 96c0-18.857 9.216-30 31-30M65 96c0 18.857 9.216 30 31 30m0 0c14.85 0 24.204-4.282 29-12"/>
                                 <path stroke-linecap="round" stroke-linejoin="round" d="M95.761 66C117.713 66 127 77.143 127 96H66"/>
                                 </g>
                                 <defs>
                                 <clipPath id="a">
                                 <path fill="#ffffff" d="M0 0h192v192H0z"/>
                                 </clipPath>
                                 </defs>
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Ebay Orders</span>
                        </a>
                     </li>
                     <!------------------------------- Laptop Stock  --------------------------------- -->
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" role="button" aria-expanded="false" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <!-- Download SVG icon from http://tabler-icons.io/i/package -->
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" />
                                 <line x1="12" y1="12" x2="20" y2="7.5" />
                                 <line x1="12" y1="12" x2="12" y2="21" />
                                 <line x1="12" y1="12" x2="4" y2="7.5" />
                                 <line x1="16" y1="5.25" x2="8" y2="9.75" />
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Stock</span>
                        </a>
                        <div class="dropdown-menu">
                           <div class="dropdown-menu-columns accstock">
                              <a class="dropdown-item" href="/stocks/orange" > <span class="btn btn-primary btn-sm btn-orange me-1 st_status_1"style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Orange - Awaiting Diagnosis">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/stocks/purple" > <span class="btn btn-primary btn-sm btn-purple me-1 st_status_2" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Purple - Awaiting Repair">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/stocks/red" > <span class="btn btn-primary btn-sm btn-red me-1 st_status_3" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Red - Faulty Needing Parts">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/stocks/lightblue" > <span class="btn btn-primary btn-sm btn-cyan me-1 st_status_4" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Light Blue - Parts Ordered">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/stocks/lightgreen" > <span class="btn btn-primary btn-sm btn-lime me-1 st_status_6" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Light Green - Awaiting Cleanup">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/stocks/darkgreen" > <span class="btn btn-primary btn-sm btn-teal me-1 st_status_7" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Green - Ready To Sell">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/stocks/brown" > <span class="btn btn-primary btn-sm btn-pinterest me-1 st_status_11" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Brown - Out of House Repair">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/stocks/darkblue" > <span class="btn btn-primary btn-sm btn-indigo me-1 st_status_5" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Dark Blue - Motherboard Returned Unfixed">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/stocks/action" > <span class="btn btn-primary btn-sm btn-dribbble me-1 st_status_17" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Actions Requested">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/stocks/actioncmp" > <span class="btn btn-primary btn-sm btn-rss me-1 st_status_18" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Actions Completed">0</span>&nbsp;</a>
                           </div>
                        </div>
                     </li>
                     <!------------------------------- Laptop Archive  --------------------------------- -->
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" role="button" aria-expanded="false" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <!-- Download SVG icon from http://tabler-icons.io/i/archive -->
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <rect x="3" y="4" width="18" height="4" rx="2" />
                                 <path d="M5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-10" />
                                 <line x1="10" y1="12" x2="14" y2="12" />
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Archived</span>
                        </a>
                        <div class="dropdown-menu">
                           <div class="dropdown-menu-columns accstock">
                              <a class="dropdown-item" href="/stocks/sold" > <span class="btn btn-primary btn-sm btn-azure me-1 st_status_16"style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Sold Laptops">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/stocks/gray" > <span class="btn btn-primary btn-sm btn-blue me-1 st_status_9"style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Despatched Laptops">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/stocks/black" > <span class="btn btn-primary btn-sm btn-secondary me-1 st_status_8"style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Written Off - To Be Stripped">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/stocks/stripped" > <span class="btn btn-primary btn-sm btn-dark me-1 st_status_24"style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Stripped Down Laptops">0</span>&nbsp;</a>
                           </div>
                        </div>
                     </li>
                     <!------------------------------  Laptops Admin  -------------------------------------- -->
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" role="button" aria-expanded="false" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <!-- Download SVG icon from http://tabler-icons.io/i/settings -->
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                                 <circle cx="12" cy="12" r="3" />
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Admin</span>
                        </a>
                        <div class="dropdown-menu">
                           <a class="dropdown-item" href="/admin/products" > Products </a>
                           <a class="dropdown-item" href="/admin/manufacturers" > Manufacturers </a>
                        </div>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
         <!------------------------------- Dell Components Accordian  --------------------------------- -->
         <div class="accordion-item">
            <h2 class="accordion-header" id="panelsStayOpen-headingThree">
               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                  <!-- Download SVG icon from http://tabler-icons.io/i/radioactive -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                     <path d="M13.5 14.6l3 5.19a9 9 0 0 0 4.5 -7.79h-6a3 3 0 0 1 -1.5 2.6" />
                     <path d="M13.5 9.4l3 -5.19a9 9 0 0 0 -9 0l3 5.19a3 3 0 0 1 3 0" />
                     <path d="M10.5 14.6l-3 5.19a9 9 0 0 1 -4.5 -7.79h6a3 3 0 0 0 1.5 2.6" />
                  </svg>
                  &nbsp;Dell Components
               </button>
            </h2>
            <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
               <div class="accordion-body">
                  <ul class="navbar-nav pt-lg-3">
                     <!------------------------------  Dell Components Orders  -------------------------------------- -->
                     <li class="nav-item">
                        <a class="nav-link" href="/componentorders" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <line x1="9" y1="6" x2="20" y2="6" />
                                 <line x1="9" y1="12" x2="20" y2="12" />
                                 <line x1="9" y1="18" x2="20" y2="18" />
                                 <line x1="5" y1="6" x2="5" y2="6.01" />
                                 <line x1="5" y1="12" x2="5" y2="12.01" />
                                 <line x1="5" y1="18" x2="5" y2="18.01" />
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Orders</span>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="/strippedlaptops" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <line x1="9" y1="6" x2="20" y2="6" />
                                 <line x1="9" y1="12" x2="20" y2="12" />
                                 <line x1="9" y1="18" x2="20" y2="18" />
                                 <line x1="5" y1="6" x2="5" y2="6.01" />
                                 <line x1="5" y1="12" x2="5" y2="12.01" />
                                 <line x1="5" y1="18" x2="5" y2="18.01" />
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Stripped Laptops</span>
                        </a>
                     </li>
                     <!------------------------------  Dell Components Stock  -------------------------------------- -->
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" role="button" aria-expanded="false" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <!-- Download SVG icon from http://tabler-icons.io/i/package -->
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <polyline points="12 3 20 7.5 20 16.5 12 21 4 16.5 4 7.5 12 3" />
                                 <line x1="12" y1="12" x2="20" y2="7.5" />
                                 <line x1="12" y1="12" x2="12" y2="21" />
                                 <line x1="12" y1="12" x2="4" y2="7.5" />
                                 <line x1="16" y1="5.25" x2="8" y2="9.75" />
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Stock</span>
                        </a>
                        <div class="dropdown-menu">
                           <div class="dropdown-menu-columns componentstock">
                              <a class="dropdown-item" href="/componentstocks/orange" > <span class="btn btn-primary btn-sm btn-orange me-1 dst_status_1"style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Orange - Awaiting Diagnosis">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/componentstocks/lightgreen" > <span class="btn btn-primary btn-sm btn-lime me-1 dst_status_6" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Light Green - Ready To Sell (Grade B)">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/componentstocks/green" > <span class="btn btn-primary btn-sm btn-green me-1 dst_status_22" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Green - Ready To Sell (Grade A)">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/componentstocks/darkgreen" > <span class="btn btn-primary btn-sm btn-teal me-1 dst_status_7" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Dark Green - Ready To Sell (New)">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/componentstocks/usedinternal" > <span class="btn btn-primary btn-sm btn-cyan me-1 dst_status_30"style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Used Internally">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/componentstocks/sold" > <span class="btn btn-primary btn-sm btn-secondary me-1 dst_status_16" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Sold Items">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/componentstocks/senttofba" > <span class="btn btn-primary btn-sm btn-secondary me-1 dst_status_29" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Sent to FBA">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/componentstocks/black" > <span class="btn btn-primary btn-sm btn-dark me-1 dst_status_8" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Written Off Items">0</span>&nbsp;</a>
<a class="dropdown-item" href="/componentstocks/brown" > <span class="btn btn-primary btn-sm btn-pinterest me-1 dst_status_11"style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Brown - Out-of-House Work">0</span>&nbsp;</a>


                           </div>
                        </div>
                     </li>
                     <!------------------------------  Dell Components Products  -------------------------------------- -->
                     <li class="nav-item">
                        <a class="nav-link" href="/tool/dell_part_finder" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-alien" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <desc>Download more icon variants from https://tabler-icons.io/i/alien</desc>
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                 <path d="M11 17a2.5 2.5 0 0 0 2 0"></path>
                                 <path d="M12 3c-4.664 0 -7.396 2.331 -7.862 5.595a11.816 11.816 0 0 0 2 8.592a10.777 10.777 0 0 0 3.199 3.064c1.666 1 3.664 1 5.33 0a10.777 10.777 0 0 0 3.199 -3.064a11.89 11.89 0 0 0 2 -8.592c-.466 -3.265 -3.198 -5.595 -7.862 -5.595z"></path>
                                 <line x1="8" y1="11" x2="10" y2="13"></line>
                                 <line x1="16" y1="11" x2="14" y2="13"></line>
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Products</span>
                        </a>
                     </li>
                     <!------------------------------  Dell Components Admin  -------------------------------------- -->
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" role="button" aria-expanded="false" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <path d="M10.325 4.317c.426 -1.756 2.924 -1.756 3.35 0a1.724 1.724 0 0 0 2.573 1.066c1.543 -.94 3.31 .826 2.37 2.37a1.724 1.724 0 0 0 1.065 2.572c1.756 .426 1.756 2.924 0 3.35a1.724 1.724 0 0 0 -1.066 2.573c.94 1.543 -.826 3.31 -2.37 2.37a1.724 1.724 0 0 0 -2.572 1.065c-.426 1.756 -2.924 1.756 -3.35 0a1.724 1.724 0 0 0 -2.573 -1.066c-1.543 .94 -3.31 -.826 -2.37 -2.37a1.724 1.724 0 0 0 -1.065 -2.572c-1.756 -.426 -1.756 -2.924 0 -3.35a1.724 1.724 0 0 0 1.066 -2.573c-.94 -1.543 .826 -3.31 2.37 -2.37c1 .608 2.296 .07 2.572 -1.065z" />
                                 <circle cx="12" cy="12" r="3" />
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Admin</span>
                        </a>
                        <div class="dropdown-menu">
                           <a class="dropdown-item" href="/tool/dell_part_loader" > Dell Part Loader </a>
                        </div>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
         <!------------------------------  Returns Accordian -------------------------------------- -->
         <div class="accordion-item">
            <h2 class="accordion-header" id="panelsStayOpen-headingEight">
               <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseEight" aria-expanded="false" aria-controls="panelsStayOpen-collapseEight">
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                     <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                        <circle cx="7" cy="17" r="2" />
                        <circle cx="17" cy="17" r="2" />
                        <path d="M5 17h-2v-11a1 1 0 0 1 1 -1h9v6h-5l2 2m0 -4l-2 2" />
                        <line x1="9" y1="17" x2="15" y2="17" />
                        <path d="M13 6h5l3 5v6h-2" />
                     </svg>
                  </span>
                  Returns
               </button>
            </h2>
            <div id="panelsStayOpen-collapseEight" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingEight">
               <div class="accordion-body">
                  <ul class="navbar-nav pt-lg-3">
                     <!------------------------------  Component Return  -------------------------------------- -->
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" role="button" aria-expanded="false" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <path d="M9 13l-4 -4l4 -4m-4 4h11a4 4 0 0 1 0 8h-1" />
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Component Returns</span>
                        </a>
                        <div class="dropdown-menu">
                           <div class="dropdown-menu-columns accstock">
                              <a class="dropdown-item" href="/newrmac" > <span class="btn btn-primary btn-sm btn-dark me-1"style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Create New Component Return">NEW</span>&nbsp;</a>
                              <a class="dropdown-item" href="/rmac/orange" > <span class="btn btn-primary btn-sm btn-orange me-1 rmac_status_1"style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Awaiting Diagnosis">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/rmac/red" > <span class="btn btn-primary btn-sm btn-red me-1 rma_status_3" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Faulty Needing Parts">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/rmac/yellow" > <span class="btn btn-primary btn-sm btn-yellow me-1 rma_status_3" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Confirmed Faulty">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/rmac/darkgreen" > <span class="btn btn-primary btn-sm btn-teal me-1 rmac_status_7" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Confirmed OK">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/rmac/pink" > <span class="btn btn-primary btn-sm btn-pink me-1 rmac_status_7" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Courier Claim">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/rmac/purple" > <span class="btn btn-primary btn-sm btn-purple me-1 rmac_status_7" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Replaced for Customer Without Return">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/rmac/azure" > <span class="btn btn-primary btn-sm btn-azure me-1 rmac_status_7" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Returned to Supplier">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/rmac/indigo" > <span class="btn btn-primary btn-sm btn-indigo me-1 rmac_status_7" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Credited by Supplier">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/rmac/cyan" > <span class="btn btn-primary btn-sm btn-cyan me-1 rmac_status_7" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Rejected by Supplier">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/rmac/danger" > <span class="btn btn-primary btn-sm btn-danger me-1 rmac_status_7" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Return Refused by Supplier">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/rmac/orange" > <span class="btn btn-primary btn-sm btn-vk me-1 rmac_status_55"style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Resolved">0</span>&nbsp;</a>
                           </div>
                        </div>
                     </li>
                     <!------------------------------  Laptop Returns  -------------------------------------- -->
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" role="button" aria-expanded="false" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <path d="M9 13l-4 -4l4 -4m-4 4h11a4 4 0 0 1 0 8h-1" />
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Laptop Returns</span>
                        </a>
                        <div class="dropdown-menu">
                           <div class="dropdown-menu-columns accstock">
                              <a class="dropdown-item" href="/rmal/new" > <span class="btn btn-primary btn-sm btn-dark me-1 rmac_status_1"style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Create New Component Return">NEW</span>&nbsp;</a>
                              <a class="dropdown-item" href="/rmal/orange" > <span class="btn btn-primary btn-sm btn-orange me-1 rmac_status_1"style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Orange - Awaiting Diagnosis">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/rmal/red" > <span class="btn btn-primary btn-sm btn-red me-1 rma_status_3" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Red - Faulty Needing Parts">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/rmac/yellow" > <span class="btn btn-primary btn-sm btn-yellow me-1 rma_status_3" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Yellow - Unfixed - Customer Refunded/Credited">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/rmac/darkgreen" > <span class="btn btn-primary btn-sm btn-teal me-1 rmac_status_7" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Fixed - Returned to Customer">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/rmac/pink" > <span class="btn btn-primary btn-sm btn-pink me-1 rmac_status_7" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Courier Claim">0</span>&nbsp;</a>
                              <a class="dropdown-item" href="/rmac/indigo" > <span class="btn btn-primary btn-sm btn-indigo me-1 rmac_status_7" style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Fixed - Returned to Stock">0</span>&nbsp;</a>
                           </div>
                        </div>
                     </li>
                     <!------------------------------  Supplier Returns  -------------------------------------- -->
                     <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#navbar-extra" data-bs-toggle="dropdown" role="button" aria-expanded="false" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                 <path style="fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round;stroke:currentColor;stroke-opacity:1;stroke-miterlimit:10;" d="M 15.910319 12.349447 L 14.518717 7.328288 C 14.429199 7.006836 14.608236 6.677246 14.921549 6.57959 L 19.881673 4.968262 " transform="matrix(0.96,0,0,0.96,0.48,0)"/>
                                 <path style="fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round;stroke:currentColor;stroke-opacity:1;stroke-miterlimit:10;" d="M 14.018229 19.787598 C 17.021159 20.707194 20.268229 18.929036 21.281413 15.820313 C 22.290527 12.70752 20.671061 9.448242 17.668132 8.540853 " transform="matrix(0.96,0,0,0.96,0.48,0)"/>
                                 <path style="fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round;stroke:currentColor;stroke-opacity:1;stroke-miterlimit:10;" d="M 2.421549 12.5 L 11.450684 12.5 " transform="matrix(0.96,0,0,0.96,0.48,0)"/>
                                 <path style="fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round;stroke:currentColor;stroke-opacity:1;stroke-miterlimit:10;" d="M 2.421549 6.628418 L 11.450684 6.628418 " transform="matrix(0.96,0,0,0.96,0.48,0)"/>
                                 <path style="fill:none;stroke-width:1.5;stroke-linecap:round;stroke-linejoin:round;stroke:currentColor;stroke-opacity:1;stroke-miterlimit:10;" d="M 2.421549 18.367513 L 11.450684 18.367513 " transform="matrix(0.96,0,0,0.96,0.48,0)"/>
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Supplier Returns</span>
                        </a>
                        <div class="dropdown-menu">
                           <a class="dropdown-item" href="/supplierrmac"> Outstanding Returns </a>
                        </div>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
         <!------------------------------- Tools Accordion  --------------------------------- -->
         <div class="accordion-item">
            <h2 class="accordion-header" id="panelsStayOpen-headingFour">
               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFour" aria-expanded="false" aria-controls="panelsStayOpen-collapseFour">
                  <!-- Download SVG icon from http://tabler-icons.io/i/tools -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                     <path d="M3 21h4l13 -13a1.5 1.5 0 0 0 -4 -4l-13 13v4" />
                     <line x1="14.5" y1="5.5" x2="18.5" y2="9.5" />
                     <polyline points="12 8 7 3 3 7 8 12" />
                     <line x1="7" y1="8" x2="5.5" y2="9.5" />
                     <polyline points="16 12 21 17 17 21 12 16" />
                     <line x1="16" y1="17" x2="14.5" y2="18.5" />
                  </svg>
                  &nbsp;Tools
               </button>
            </h2>
            <div id="panelsStayOpen-collapseFour" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingFour">
               <div class="accordion-body">
                  <ul class="navbar-nav pt-lg-3">
                     <!------------------------------- Warehouse Barcode Label  --------------------------------- -->
                     <li class="nav-item">
                        <a class="nav-link" href="/tool/warehouse_barcodes_creator" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <!-- Download SVG icon from http://tabler-icons.io/i/barcode -->
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <path d="M4 7v-1a2 2 0 0 1 2 -2h2" />
                                 <path d="M4 17v1a2 2 0 0 0 2 2h2" />
                                 <path d="M16 4h2a2 2 0 0 1 2 2v1" />
                                 <path d="M16 20h2a2 2 0 0 0 2 -2v-1" />
                                 <rect x="5" y="11" width="1" height="2" />
                                 <line x1="10" y1="11" x2="10" y2="13" />
                                 <rect x="14" y="11" width="1" height="2" />
                                 <line x1="19" y1="11" x2="19" y2="13" />
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Warehouse Barcodes Creator</span>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link" href="/tool/warehouse_stocktake" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <rect x="7" y="3" width="14" height="14" rx="2" />
                                 <circle cx="14" cy="8" r="2" />
                                 <path d="M12 12a2 2 0 1 0 4 0v-4" />
                                 <path d="M17 17v2a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-10a2 2 0 0 1 2 -2h2" />
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Warehouse Stocktake</span>
                        </a>
                        <a class="nav-link" href="/productlookup" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                 <circle cx="10" cy="10" r="7"></circle>
                                 <path d="M21 21l-6 -6"></path>
                                 <line x1="10" y1="13" x2="10" y2="13.01"></line>
                                 <path d="M10 10a1.5 1.5 0 1 0 -1.14 -2.474"></path>
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Product Lookup</span>
                        </a>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
         <?php
            if ($_SESSION["user_role"] == "3")
            {

            ?>
         <!------------------------------- Reports Accordian  --------------------------------- -->
         <div class="accordion-item">
            <h2 class="accordion-header" id="panelsStayOpen-headingFive">
               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseFive" aria-expanded="false" aria-controls="panelsStayOpen-collapseFive">
                  <!-- Download SVG icon from http://tabler-icons.io/i/news -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                     <path d="M16 6h3a1 1 0 0 1 1 1v11a2 2 0 0 1 -4 0v-13a1 1 0 0 0 -1 -1h-10a1 1 0 0 0 -1 1v12a3 3 0 0 0 3 3h11" />
                     <line x1="8" y1="8" x2="12" y2="8" />
                     <line x1="8" y1="12" x2="12" y2="12" />
                     <line x1="8" y1="16" x2="12" y2="16" />
                  </svg>
                  &nbsp;Reports
               </button>
            </h2>
            <div id="panelsStayOpen-collapseFive" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingFive">
               <div class="accordion-body">
                  <ul class="navbar-nav pt-lg-3">
                     <!------------------------------- Laptops - Profit/Loss by Order Report  --------------------------------- -->
                     <li class="nav-item">
                        <a class="nav-link" href="/report/laptop_order_profit" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg fill="currentColor" height="24" width="24" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                                     viewBox="0 0 359.967 359.967" xml:space="preserve">
                                 <path id="XMLID_106_" d="M269.967,136.038V89.996c0-22.002-15.426-41.832-43.436-55.836c-24.705-12.352-57.217-19.155-91.547-19.155
                                    c-34.334,0-66.847,6.803-91.551,19.155C15.425,48.164,0,67.994,0,89.996v59.992v59.99v59.992c0,22.003,15.425,41.832,43.434,55.837
                                    c24.704,12.352,57.217,19.154,91.551,19.154c26.155,0,51.089-3.902,72.629-11.302c14.236,7.224,30.327,11.302,47.354,11.302
                                    c57.897,0,105-47.103,105-105C359.967,187.157,320.784,143.339,269.967,136.038z M30,198.013c4.091,2.765,8.567,5.378,13.434,7.811
                                    c24.704,12.352,57.218,19.155,91.551,19.155c5.414,0,10.796-0.179,16.128-0.516c-0.751,5.059-1.146,10.234-1.146,15.499
                                    c0,4.899,0.345,9.72,0.998,14.442c-5.271,0.376-10.609,0.566-15.98,0.566C74.897,254.971,30,231.217,30,209.979V198.013z
                                     M160.833,193.483c-8.422,0.991-17.084,1.495-25.849,1.495C74.897,194.979,30,171.226,30,149.988v-11.966
                                    c4.091,2.765,8.567,5.378,13.434,7.81c24.704,12.353,57.218,19.155,91.551,19.155c18.472,0,36.403-1.986,52.89-5.73
                                    C176.64,168.613,167.387,180.264,160.833,193.483z M134.984,45.005c60.086,0,104.982,23.753,104.982,44.991
                                    c0,21.238-44.896,44.992-104.982,44.992C74.897,134.988,30,111.234,30,89.996C30,68.758,74.897,45.005,134.984,45.005z M30,269.971
                                    v-11.965c4.091,2.765,8.567,5.377,13.434,7.81c24.704,12.352,57.217,19.155,91.551,19.155c8.28,0,16.502-0.407,24.573-1.194
                                    c4.576,9.925,10.653,19.021,17.943,26.99c-13.367,2.737-27.84,4.195-42.517,4.195C74.897,314.962,30,291.208,30,269.971z
                                     M254.967,314.962c-41.355,0-75-33.645-75-75c0-41.238,33.457-74.802,74.652-74.991c0.117,0.003,0.23,0.018,0.348,0.018
                                    s0.23-0.015,0.348-0.018c41.195,0.189,74.652,33.753,74.652,74.991C329.967,281.317,296.322,314.962,254.967,314.962z"/>
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Laptop Order Profit</span>
                        </a>
                        <a class="nav-link" href="/report/laptop_sales_date" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg fill="currentColor" height="24" width="24" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                                     viewBox="0 0 359.967 359.967" xml:space="preserve">
                                 <path id="XMLID_106_" d="M269.967,136.038V89.996c0-22.002-15.426-41.832-43.436-55.836c-24.705-12.352-57.217-19.155-91.547-19.155
                                    c-34.334,0-66.847,6.803-91.551,19.155C15.425,48.164,0,67.994,0,89.996v59.992v59.99v59.992c0,22.003,15.425,41.832,43.434,55.837
                                    c24.704,12.352,57.217,19.154,91.551,19.154c26.155,0,51.089-3.902,72.629-11.302c14.236,7.224,30.327,11.302,47.354,11.302
                                    c57.897,0,105-47.103,105-105C359.967,187.157,320.784,143.339,269.967,136.038z M30,198.013c4.091,2.765,8.567,5.378,13.434,7.811
                                    c24.704,12.352,57.218,19.155,91.551,19.155c5.414,0,10.796-0.179,16.128-0.516c-0.751,5.059-1.146,10.234-1.146,15.499
                                    c0,4.899,0.345,9.72,0.998,14.442c-5.271,0.376-10.609,0.566-15.98,0.566C74.897,254.971,30,231.217,30,209.979V198.013z
                                     M160.833,193.483c-8.422,0.991-17.084,1.495-25.849,1.495C74.897,194.979,30,171.226,30,149.988v-11.966
                                    c4.091,2.765,8.567,5.378,13.434,7.81c24.704,12.353,57.218,19.155,91.551,19.155c18.472,0,36.403-1.986,52.89-5.73
                                    C176.64,168.613,167.387,180.264,160.833,193.483z M134.984,45.005c60.086,0,104.982,23.753,104.982,44.991
                                    c0,21.238-44.896,44.992-104.982,44.992C74.897,134.988,30,111.234,30,89.996C30,68.758,74.897,45.005,134.984,45.005z M30,269.971
                                    v-11.965c4.091,2.765,8.567,5.377,13.434,7.81c24.704,12.352,57.217,19.155,91.551,19.155c8.28,0,16.502-0.407,24.573-1.194
                                    c4.576,9.925,10.653,19.021,17.943,26.99c-13.367,2.737-27.84,4.195-42.517,4.195C74.897,314.962,30,291.208,30,269.971z
                                     M254.967,314.962c-41.355,0-75-33.645-75-75c0-41.238,33.457-74.802,74.652-74.991c0.117,0.003,0.23,0.018,0.348,0.018
                                    s0.23-0.015,0.348-0.018c41.195,0.189,74.652,33.753,74.652,74.991C329.967,281.317,296.322,314.962,254.967,314.962z"/>
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Laptop Sales by Date</span>
                        </a>
                        <a class="nav-link" href="/report/laptop_stock" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M0 4.6A2.6 2.6 0 0 1 2.6 2h18.8A2.6 2.6 0 0 1 24 4.6v.8A2.6 2.6 0 0 1 21.4 8H21v10.6c0 1.33-1.07 2.4-2.4 2.4H5.4C4.07 21 3 19.93 3 18.6V8h-.4A2.6 2.6 0 0 1 0 5.4v-.8ZM2.6 4a.6.6 0 0 0-.6.6v.8a.6.6 0 0 0 .6.6h18.8a.6.6 0 0 0 .6-.6v-.8a.6.6 0 0 0-.6-.6H2.6ZM8 10a1 1 0 1 0 0 2h8a1 1 0 1 0 0-2H8Z" fill="currentColor"/></svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Laptop Stock Report</span>
                        </a>
                        <a class="nav-link" href="/report/nwp_stock" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg width="24" height="24" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" stroke-width="3" stroke="currentColor" fill="none"><path d="M30,52.16c.81-2.07,7.06-17,19.76-19.86a.09.09,0,0,0,0-.18c-2.14-.86-15.22-6.57-19.38-20.26a.09.09,0,0,0-.18,0c-.51,2.27-3.94,14.43-20,20a.1.1,0,0,0,0,.19c2.24.38,13.48,3.14,19.62,20.15A.1.1,0,0,0,30,52.16Z"/><path d="M48.79,25.08c.29-.74,2.52-6.07,7.06-7.09a0,0,0,0,0,0-.07c-.76-.3-5.43-2.34-6.92-7.23a0,0,0,0,0-.07,0c-.18.82-1.4,5.16-7.14,7.13a0,0,0,0,0,0,.07c.8.14,4.81,1.12,7,7.2A0,0,0,0,0,48.79,25.08Z"/></svg>
                           </span>
                           <span class="nav-link-title">&nbsp;New Products stock report</span>
                        </a>
                        <a class="nav-link" href="/report/dp_stock" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg width="24" height="24" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" stroke-width="3" stroke="currentColor" fill="none"><path d="M30,52.16c.81-2.07,7.06-17,19.76-19.86a.09.09,0,0,0,0-.18c-2.14-.86-15.22-6.57-19.38-20.26a.09.09,0,0,0-.18,0c-.51,2.27-3.94,14.43-20,20a.1.1,0,0,0,0,.19c2.24.38,13.48,3.14,19.62,20.15A.1.1,0,0,0,30,52.16Z"/><path d="M48.79,25.08c.29-.74,2.52-6.07,7.06-7.09a0,0,0,0,0,0-.07c-.76-.3-5.43-2.34-6.92-7.23a0,0,0,0,0-.07,0c-.18.82-1.4,5.16-7.14,7.13a0,0,0,0,0,0,.07c.8.14,4.81,1.12,7,7.2A0,0,0,0,0,48.79,25.08Z"/></svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Dell Parts stock report</span>
                        </a>
                        <a class="nav-link" href="/report/acc_order_profit" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg width="24" height="24" viewBox="0 0 1024 1024" class="icon"  version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M365.71 548.57h438.86v73.14H365.71zM365.7 402.56h219.43v73.14H365.7zM365.71 694.86h438.86V768H365.71zM639.76 321.68v54.86h63.93v18.58h-61.9v54.86h61.9v59.61h54.85v-59.61h61.88v-54.86h-61.88v-18.58h63.93v-54.86h-32.09l43.84-43.84-38.78-38.79-64.32 64.33-64.33-64.33-38.78 38.79 43.84 43.84z" fill="currentColor" /><path d="M219.44 109.62v219.52H73.14v475.43c0 60.59 49.12 109.71 109.71 109.71 0.3 0 0.58-0.09 0.89-0.09h631.8c74.62 0 135.32-61.14 135.32-136.3V109.62H219.44z m-36.58 731.52c-20.17 0-36.57-16.41-36.57-36.57V402.29h73.14v402.29c0 20.01-16.18 36.23-36.13 36.48h-0.43l-0.01 0.08z m694.86-63.25c0 34.82-27.89 63.16-62.18 63.16H285.88c4.06-11.47 6.69-23.62 6.69-36.48V378.93h0.01V182.77h585.14v595.12z" fill="currentColor" /></svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Accessories Order Profit</span>
                        </a>
                        <a class="nav-link" href="/report/acc_stock" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg width="24" height="24" viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg" stroke-width="3" stroke="currentColor" fill="none"><path d="M30,52.16c.81-2.07,7.06-17,19.76-19.86a.09.09,0,0,0,0-.18c-2.14-.86-15.22-6.57-19.38-20.26a.09.09,0,0,0-.18,0c-.51,2.27-3.94,14.43-20,20a.1.1,0,0,0,0,.19c2.24.38,13.48,3.14,19.62,20.15A.1.1,0,0,0,30,52.16Z"/><path d="M48.79,25.08c.29-.74,2.52-6.07,7.06-7.09a0,0,0,0,0,0-.07c-.76-.3-5.43-2.34-6.92-7.23a0,0,0,0,0-.07,0c-.18.82-1.4,5.16-7.14,7.13a0,0,0,0,0,0,.07c.8.14,4.81,1.12,7,7.2A0,0,0,0,0,48.79,25.08Z"/></svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Accessories stock report</span>
                        </a>
                        <a class="nav-link" href="/report/profit_loss_by_order" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" />
                                 <rect x="9" y="3" width="6" height="4" rx="2" />
                                 <path d="M9 17v-5" />
                                 <path d="M12 17v-1" />
                                 <path d="M15 17v-3" />
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Laptops - Profit/Loss by Order </span>
                        </a>
                        <a class="nav-link" href="/report/faulty_acc_report" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                 <line x1="9" y1="10" x2="9.01" y2="10"></line>
                                 <line x1="15" y1="10" x2="15.01" y2="10"></line>
                                 <path d="M9.5 15.25a3.5 3.5 0 0 1 5 0"></path>
                                 <path d="M17.566 17.606a2 2 0 1 0 2.897 .03l-1.463 -1.636l-1.434 1.606z"></path>
                                 <path d="M20.865 13.517a8.937 8.937 0 0 0 .135 -1.517a9 9 0 1 0 -9 9c.69 0 1.36 -.076 2 -.222"></path>
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Faulty Accessories</span>
                        </a>
                        <a class="nav-link" href="/report/sold_laptops_check" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill-rule="evenodd" fill="currentColor" d="M 3.710938 4.445312 C 3.808594 4.378906 3.933594 4.355469 4.050781 4.378906 C 4.167969 4.398438 4.269531 4.464844 4.335938 4.5625 L 4.558594 4.816406 L 5.34375 3.867188 C 5.445312 3.734375 5.609375 3.667969 5.777344 3.691406 C 5.941406 3.714844 6.082031 3.820312 6.136719 3.972656 C 6.195312 4.125 6.15625 4.289062 6.042969 4.40625 L 4.886719 5.792969 C 4.855469 5.828125 4.820312 5.863281 4.777344 5.890625 C 4.679688 5.957031 4.554688 5.980469 4.4375 5.957031 C 4.316406 5.933594 4.214844 5.867188 4.144531 5.773438 L 3.585938 5.039062 C 3.449219 4.84375 3.503906 4.578125 3.710938 4.441406 Z M 16.699219 11.523438 C 18.796875 11.523438 20.726562 12.621094 21.726562 14.375 C 22.726562 16.132812 22.632812 18.269531 21.484375 19.941406 L 23.960938 22.507812 L 22.253906 23.992188 L 19.867188 21.5 C 17.828125 22.785156 15.152344 22.695312 13.214844 21.273438 C 11.277344 19.855469 10.503906 17.417969 11.292969 15.214844 C 12.078125 13.011719 14.25 11.527344 16.699219 11.523438 Z M 7.9375 13.972656 C 7.597656 13.953125 7.332031 13.679688 7.34375 13.351562 C 7.332031 13.195312 7.390625 13.042969 7.503906 12.925781 C 7.617188 12.808594 7.773438 12.738281 7.9375 12.734375 L 9.785156 12.734375 C 10.128906 12.753906 10.394531 13.027344 10.382812 13.351562 C 10.390625 13.511719 10.332031 13.664062 10.222656 13.78125 C 10.109375 13.898438 9.953125 13.96875 9.785156 13.972656 Z M 19.136719 8.765625 C 19.019531 9.179688 18.042969 9.242188 17.824219 8.765625 L 17.824219 1.453125 C 17.828125 1.394531 17.804688 1.34375 17.761719 1.300781 C 17.722656 1.265625 17.667969 1.242188 17.609375 1.246094 L 1.558594 1.246094 C 1.5 1.242188 1.445312 1.265625 1.40625 1.304688 C 1.367188 1.34375 1.347656 1.398438 1.351562 1.453125 L 1.351562 18.699219 C 1.347656 18.753906 1.371094 18.804688 1.40625 18.84375 C 1.445312 18.882812 1.5 18.90625 1.558594 18.90625 L 7.785156 18.90625 C 8.445312 18.972656 8.460938 20.054688 7.785156 20.148438 L 1.566406 20.148438 C 0.726562 20.144531 0.046875 19.5 0.0390625 18.699219 L 0.0390625 1.453125 C 0.0390625 1.066406 0.199219 0.695312 0.488281 0.425781 C 0.773438 0.152344 1.160156 0 1.566406 0 L 17.617188 0 C 18.019531 0 18.40625 0.152344 18.691406 0.425781 C 18.976562 0.699219 19.136719 1.066406 19.136719 1.453125 C 19.136719 8.59375 19.136719 -2.0625 19.136719 8.765625 Z M 7.933594 5.367188 C 7.589844 5.347656 7.324219 5.074219 7.335938 4.746094 C 7.324219 4.589844 7.382812 4.433594 7.496094 4.316406 C 7.609375 4.199219 7.765625 4.132812 7.933594 4.125 L 14.046875 4.125 C 14.386719 4.144531 14.652344 4.417969 14.644531 4.746094 C 14.652344 4.902344 14.59375 5.058594 14.480469 5.175781 C 14.367188 5.292969 14.210938 5.359375 14.046875 5.367188 Z M 7.933594 9.664062 C 7.589844 9.644531 7.324219 9.371094 7.335938 9.046875 C 7.324219 8.886719 7.382812 8.730469 7.496094 8.617188 C 7.609375 8.5 7.765625 8.429688 7.933594 8.425781 L 14.046875 8.425781 C 14.386719 8.441406 14.652344 8.71875 14.644531 9.046875 C 14.652344 9.203125 14.59375 9.355469 14.480469 9.472656 C 14.367188 9.589844 14.210938 9.65625 14.046875 9.664062 Z M 14.542969 17.476562 C 14.511719 17.445312 14.488281 17.414062 14.464844 17.378906 C 14.441406 17.34375 14.421875 17.308594 14.40625 17.269531 C 14.28125 16.992188 14.359375 16.671875 14.597656 16.476562 C 14.835938 16.277344 15.179688 16.25 15.449219 16.40625 C 15.492188 16.429688 15.53125 16.457031 15.566406 16.484375 C 15.769531 16.667969 15.828125 16.703125 16.050781 16.890625 L 16.238281 17.054688 L 17.792969 15.46875 C 18.453125 14.820312 19.503906 15.765625 18.84375 16.421875 L 16.90625 18.410156 L 16.8125 18.507812 C 16.539062 18.789062 16.082031 18.804688 15.785156 18.550781 L 15.644531 18.414062 C 15.527344 18.3125 15.402344 18.21875 15.277344 18.109375 C 14.988281 17.867188 14.820312 17.738281 14.550781 17.480469 Z M 16.703125 12.664062 C 19.21875 12.664062 21.257812 14.601562 21.257812 16.996094 C 21.257812 19.386719 19.21875 21.328125 16.703125 21.324219 C 14.191406 21.324219 12.152344 19.382812 12.152344 16.992188 C 12.15625 14.605469 14.191406 12.667969 16.703125 12.664062 Z M 4.875 12.421875 C 5.296875 12.417969 5.679688 12.660156 5.84375 13.035156 C 6.003906 13.40625 5.914062 13.835938 5.617188 14.121094 C 5.316406 14.40625 4.863281 14.492188 4.472656 14.335938 C 4.082031 14.179688 3.828125 13.816406 3.828125 13.414062 C 3.828125 12.863281 4.296875 12.421875 4.875 12.421875 Z M 5.308594 8.203125 C 5.390625 8.125 5.5 8.078125 5.617188 8.078125 C 5.730469 8.078125 5.84375 8.125 5.925781 8.203125 C 6.007812 8.28125 6.050781 8.386719 6.050781 8.496094 C 6.050781 8.605469 6.007812 8.710938 5.925781 8.789062 L 5.5 9.203125 L 5.925781 9.621094 C 6.09375 9.78125 6.09375 10.046875 5.925781 10.207031 C 5.753906 10.367188 5.476562 10.367188 5.308594 10.207031 L 4.886719 9.796875 L 4.460938 10.210938 C 4.382812 10.289062 4.269531 10.335938 4.15625 10.335938 C 4.039062 10.335938 3.925781 10.289062 3.847656 10.210938 C 3.675781 10.046875 3.675781 9.785156 3.847656 9.625 L 4.273438 9.207031 L 3.851562 8.789062 C 3.769531 8.710938 3.722656 8.605469 3.722656 8.496094 C 3.722656 8.386719 3.769531 8.28125 3.851562 8.203125 C 3.933594 8.125 4.042969 8.082031 4.15625 8.082031 C 4.269531 8.082031 4.375 8.125 4.457031 8.203125 L 4.878906 8.617188 Z M 5.308594 8.203125 "/></svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Sold Laptops Check</span>
                        </a>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
         <!------------------------------- Admin Accordian  --------------------------------- -->
         <div class="accordion-item">
            <h2 class="accordion-header" id="panelsStayOpen-headingSix">
               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseSix" aria-expanded="false" aria-controls="panelsStayOpen-collapseSix">
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                     <path d="M7 10h3v-3l-3.5 -3.5a6 6 0 0 1 8 8l6 6a2 2 0 0 1 -3 3l-6 -6a6 6 0 0 1 -8 -8l3.5 3.5" />
                  </svg>
                  &nbsp;Admin
               </button>
            </h2>
            <div id="panelsStayOpen-collapseSix" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingSix">
               <div class="accordion-body">
                  <ul class="navbar-nav pt-lg-3">
                     <!------------------------------- Users   --------------------------------- -->
                     <li class="nav-item">
                        <a class="nav-link" href="/admin/users" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <circle cx="9" cy="7" r="4" />
                                 <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                 <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                 <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Users</span>
                        </a>
                        <a class="nav-link" href="/admin/customers" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                 <circle cx="9" cy="7" r="4" />
                                 <path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" />
                                 <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                 <path d="M21 21v-2a4 4 0 0 0 -3 -3.85" />
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Customers</span>
                        </a>
                        <a class="nav-link" href="/supplier" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                 <line x1="3" y1="21" x2="21" y2="21"></line>
                                 <path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4"></path>
                                 <line x1="5" y1="21" x2="5" y2="10.85"></line>
                                 <line x1="19" y1="21" x2="19" y2="10.85"></line>
                                 <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4"></path>
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Supplier</span>
                        </a>
                        <!--<a class="nav-link" href="/admin/suppliers" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                 <line x1="3" y1="21" x2="21" y2="21"></line>
                                 <path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4"></path>
                                 <line x1="5" y1="21" x2="5" y2="10.85"></line>
                                 <line x1="19" y1="21" x2="19" y2="10.85"></line>
                                 <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4"></path>
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Suppliers</span>
                        </a>-->

                        <a class="nav-link" href="/admin/groups" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                 <line x1="3" y1="21" x2="21" y2="21"></line>
                                 <path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4"></path>
                                 <line x1="5" y1="21" x2="5" y2="10.85"></line>
                                 <line x1="19" y1="21" x2="19" y2="10.85"></line>
                                 <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4"></path>
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Supplier Groups</span>
                        </a>

                        <!--<a class="nav-link" href="/admin/supplier_groups" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                 <line x1="3" y1="21" x2="21" y2="21"></line>
                                 <path d="M3 7v1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1m0 1a3 3 0 0 0 6 0v-1h-18l2 -4h14l2 4"></path>
                                 <line x1="5" y1="21" x2="5" y2="10.85"></line>
                                 <line x1="19" y1="21" x2="19" y2="10.85"></line>
                                 <path d="M9 21v-4a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v4"></path>
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Supplier Groups</span>
                        </a>-->
                        <a class="nav-link" href="/admin/categories" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                 <rect x="4" y="4" width="6" height="6" rx="1"></rect>
                                 <rect x="4" y="14" width="6" height="6" rx="1"></rect>
                                 <rect x="14" y="14" width="6" height="6" rx="1"></rect>
                                 <line x1="14" y1="7" x2="20" y2="7"></line>
                                 <line x1="17" y1="4" x2="17" y2="10"></line>
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Categories</span>
                        </a>
                        <a class="nav-link" href="/admin/imap" >
                           <span class="nav-link-icon d-md-none d-lg-inline-block">
                              <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                 <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                 <rect x="4" y="4" width="6" height="6" rx="1"></rect>
                                 <rect x="4" y="14" width="6" height="6" rx="1"></rect>
                                 <rect x="14" y="14" width="6" height="6" rx="1"></rect>
                                 <line x1="14" y1="7" x2="20" y2="7"></line>
                                 <line x1="17" y1="4" x2="17" y2="10"></line>
                              </svg>
                           </span>
                           <span class="nav-link-title">&nbsp;Imap Setting</span>
                        </a>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
         <?php
            } ?>
      </div>
      <!-------------------------- Secure Logout -------------------------- -->
      <ul class="navbar-nav pt-lg-3">
         <li class="nav-item" style="margin-left:30px;">
            <a class="nav-link" href="/logout" >
               <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <!-- Download SVG icon from http://tabler-icons.io/i/logout -->
                  <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                     <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                     <path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2" />
                     <path d="M7 12h14l-3 -3m0 6l3 -3" />
                  </svg>
               </span>
               <span class="nav-link-title">&nbsp;Secure Logout </span>
            </a>
         </li>
      </ul>
      <!-----------------------------  Bottom Buttons -------------------------- -->
      <a href="/invoices"  class="btn btn-cyan" style="margin:20px 20px 0px 20px;">

         <svg width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12.854,5.183h3.667a.341.341,0,0,1,0,.682H12.854A.341.341,0,0,1,12.854,5.183Zm-2.24.682h.423a.341.341,0,0,0,0-.682h-.423A.341.341,0,0,0,10.614,5.865Zm2.24-2.273h3.667a.341.341,0,0,0,0-.681H12.854A.341.341,0,0,0,12.854,3.592Zm-2.24,4.545h2.757a.341.341,0,0,0,0-.681H10.614A.341.341,0,0,0,10.614,8.137Zm0,2.272h1.848a.341.341,0,0,0,0-.681H10.614A.341.341,0,0,0,10.614,10.409Zm0,2.273h1.848a.341.341,0,0,0,0-.682H10.614A.341.341,0,0,0,10.614,12.682Zm3.1,1.931a.341.341,0,0,0-.341-.34H10.614a.341.341,0,1,0,0,.681h2.757A.341.341,0,0,0,13.712,14.613Zm-3.1-11.021h.423a.341.341,0,0,0,0-.681h-.423A.341.341,0,0,0,10.614,3.592Zm5.907,15.225H12.854a.341.341,0,0,0,0,.682h3.667A.341.341,0,0,0,16.521,18.817Zm-5.484,0h-.423a.341.341,0,0,0,0,.682h.423A.341.341,0,0,0,11.037,18.817Zm1.477-1.931a.34.34,0,0,0,.34.34h3.667a.341.341,0,1,0,0-.681H12.854A.34.34,0,0,0,12.514,16.886Zm-1.477-.341h-.423a.341.341,0,0,0,0,.681h.423A.341.341,0,0,0,11.037,16.545Zm7.368-.885v6.494a.343.343,0,0,1-.518.291l-2.071-1.256c-.04,0-2.26,1.424-2.249,1.306.006.115-2.206-1.3-2.248-1.306L9.247,22.445a.343.343,0,0,1-.517-.291V16.066H2.524A2.527,2.527,0,0,1,0,13.542V5.971a.344.344,0,0,1,.522-.289l2,1.251L4.365,5.782V2.526A2.542,2.542,0,0,1,6.888,0h8.994a2.526,2.526,0,0,1,2.523,2.524v4.31A4.429,4.429,0,0,1,18.405,15.66ZM4.365,13.542V6.586L2.7,7.624a.341.341,0,0,1-.361,0L.682,6.586v6.956a1.842,1.842,0,1,0,3.683,0ZM8.73,2.524A1.844,1.844,0,0,0,6.888.682h0A1.843,1.843,0,0,0,5.047,2.524V13.542a2.516,2.516,0,0,1-.8,1.842H8.73ZM17.723,15.66a4.429,4.429,0,0,1,0-8.826V2.524A1.843,1.843,0,0,0,15.882.682H8.611a2.515,2.515,0,0,1,.8,1.842V21.548a19.453,19.453,0,0,1,1.907-1.1c-.008-.118,2.208,1.3,2.248,1.3L15.639,20.5a.339.339,0,0,1,.353,0l1.731,1.049ZM18.064,7.5a3.749,3.749,0,0,0,0,7.5A3.749,3.749,0,0,0,18.064,7.5Zm0,3.409c-.555,0-.836-.246-.836-.729-.02-.806,1.345-.989,1.621-.249a.341.341,0,0,0,.629-.264A1.475,1.475,0,0,0,18.4,8.808c.017-.265.011-.623-.341-.628s-.358.363-.341.627a1.4,1.4,0,0,0,.341,2.781c.558,0,.84.246.84.729.02.806-1.345.989-1.621.249a.34.34,0,0,0-.446-.182c-.641.437.39,1.238.886,1.3-.016.264-.011.622.341.628s.358-.362.341-.626A1.4,1.4,0,0,0,18.062,10.907Z" fill="#ffffff"/></svg>
         &nbsp;Invoices
      </a>
      <a href="/sales"  class="btn btn-lime" style="margin:20px 20px;">
         <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
         <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 512 512">
            <defs>
               <style>.cls-1{fill:#ffffff;}</style>
            </defs>
            <title>wheel-cart-outline</title>
            <path class="cls-1" d="M499.94,86.36A57.22,57.22,0,0,0,454.37,64H89.95L76.42,9.71A12.79,12.79,0,0,0,64,0H12.8a12.8,12.8,0,1,0,0,25.6H54l97.71,392a51.19,51.19,0,1,0,77.07,56H347.22a51.2,51.2,0,1,0,0-25.6H228.78a51.29,51.29,0,0,0-49.58-38.4c-1,0-2.06,0-3.08.11L163.33,358.4H407.83a57.61,57.61,0,0,0,55.75-43.12l46.55-179.2A57.24,57.24,0,0,0,499.94,86.36ZM396.8,435.2a25.6,25.6,0,1,1-25.6,25.6A25.63,25.63,0,0,1,396.8,435.2Zm-192,25.6a25.6,25.6,0,1,1-25.6-25.6A25.63,25.63,0,0,1,204.8,460.8Zm234-152a32,32,0,0,1-31,24H157.34l-16-64H449.2Zm17-65.64H135l-16-64H472.48Zm29.49-113.56-6.22,24H112.68l-16-64H454.37a32,32,0,0,1,31,40Z"/>
         </svg>
         Sales
      </a>
      <a href="/label"  class="btn btn-teal" style="margin:0 20px;">
         <!-- Download SVG icon from http://tabler-icons.io/i/printer -->
         <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M17 17h2a2 2 0 0 0 2 -2v-4a2 2 0 0 0 -2 -2h-14a2 2 0 0 0 -2 2v4a2 2 0 0 0 2 2h2" />
            <path d="M17 9v-4a2 2 0 0 0 -2 -2h-6a2 2 0 0 0 -2 2v4" />
            <rect x="7" y="13" width="10" height="8" rx="2" />
         </svg>
         Labels
      </a>
      <a href="/dispatch" class="btn btn-pink" style="margin:20px;">
         <!-- Download SVG icon from http://tabler-icons.io/i/door-exit -->
         <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M13 12v.01" />
            <path d="M3 21h18" />
            <path d="M5 21v-16a2 2 0 0 1 2 -2h7.5m2.5 10.5v7.5" />
            <path d="M14 7h7m-3 -3l3 3l-3 3" />
         </svg>
         Dispatch
      </a>
   </div>
</aside>
<script>
   $(document).ready(function(){
   setInterval('updateClock()', 1000);

   $(document).on('hide.bs.dropdown', '.keep-open', function (e) { console.log(e);
     e.stopPropagation(); return false;
   });

   });

   function updateClock (){
   var currentTime = new Date ( );
   var currentHours = currentTime.getHours ( );
   var currentMinutes = currentTime.getMinutes ( );
   var currentSeconds = currentTime.getSeconds ( );

   // Pad the minutes and seconds with leading zeros, if required
   currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;
   currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;

   // Choose either "AM" or "PM" as appropriate
   var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";

   // Convert the hours component to 12-hour format if needed
   currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;

   // Convert an hours component of "0" to "12"
   currentHours = ( currentHours == 0 ) ? 12 : currentHours;

   // Compose the string for display
   var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;


   $("#clock").html( currentTime.toDateString()+" "+  currentTimeString);
   }

   function setCounters() {
     $.post("/stocksajax/counts",{"action":"counts"})
       .then(function(a) {
         ja = (JSON.parse(a));
         for(var i=0;i<ja.data.length;i++) {
             var d = ja.data[i];
             var key = "st_status_"+d["st_status"];
   var data2d_data = ja.data2.filter(function(a,b){ return a.st_status == d["st_status"]; });
             $('.'+key).empty();
             if(data2d_data.length>0 && d["st_status"]=="7") {
   	$('.'+key).append("<span class='c'>"+d["c"]+" ("+data2d_data[0]["c"]+")</span>");
   }
   else
             		$('.'+key).append("<span class='c'>"+d["c"]+"</span>");          }

         $.post("/accstocksajax/counts",{"action":"counts"})
         .then(function(a) {
           ja = (JSON.parse(a));
           for(var i=0;i<ja.data.length;i++) {
               var d = ja.data[i];
               var key = "ast_status_"+d["ast_status"];
               $('.'+key).empty();
               $('.'+key).append("<span class='c'>"+d["c"]+"</span>");
           }

   $.post("/newitemstocksajax/counts",{"action":"counts"})
   .then(function(a) {
   ja = (JSON.parse(a));
   for(var i=0;i<ja.data.length;i++) {
   	var d = ja.data[i];
   	var key = "newitems_status_"+d["nst_status"];
   	$('.'+key).empty();
   	$('.'+key).append("<span class='c'>"+d["c"]+"</span>");
   }

   $.post("/componentstocksajax/counts",{"action":"counts"})
   .then(function(a) {
   	ja = (JSON.parse(a));
   	for(var i=0;i<ja.data.length;i++) {
   		var d = ja.data[i];
   		var key = "dst_status_"+d["dst_status"];
   		$('.'+key).empty();
   		$('.'+key).append("<span class='c'>"+d["c"]+"</span>");
   	}

   	$.post("/newrmacstocksajax/counts",{"action":"counts"})
   	.then(function(a) {
   		ja = (JSON.parse(a));
   		for(var i=0;i<ja.data.length;i++) {
   			var d = ja.data[i];
   			var key = "rmac_status_"+d["rmac_status"];
   			$('.'+key).empty();
   			$('.'+key).append("<span class='c'>"+d["c"]+"</span>");
   		}

   		setTimeout(setCounters, 60000); //1 minute
   	});
   });

   });

         });



       });

   }

   setTimeout(setCounters, 1000); //1 minute


   function GoSearch(ev) {
     if(ev.code=="Enter") {
       var val = $('.search-menu').val();
       console.log("searching...", val);
       location.href="/stocks/search?search="+val;

     }
   }

</script>
<div class="page-wrapper">
