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
                           <span class="nav-link-title">&nbsp;Stock</span>
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
                              <a class="dropdown-item" href="/rmac/orange" > <span class="btn btn-primary btn-sm btn-vk me-1 rmac_status_1"style="width:100%;" data-bs-toggle="tooltip" data-bs-placement="right" title="Resolved">0</span>&nbsp;</a>
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
                  </ul>
               </div>
            </div>
         </div>
         <!------------------------------- Tools Accordian  --------------------------------- -->
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
      <a href="/sales"  class="btn btn-lime" style="margin:20px 20px 20px 20px;">
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
