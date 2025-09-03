 

<!doctype html>
 
 <html lang="en" id="htmltag" class="color-sidebar sidebarcolor5">
 
  
 <head>
     <style>
 input[type='checkbox'] {
   -moz-appearance: none;
   -webkit-appearance: none;
   appearance: none;
   vertical-align: middle;
   outline: none;
   font-size: inherit;
   cursor: pointer;
   width: 1.0em;
   height: 1.0em;
   background: white;
   border-radius: 0.25em;
   border: 0.125em solid #555;
   position: relative;
 }
 
 input[type='checkbox']:checked {
   background: #adf;
 }
 
 input[type='checkbox']:checked:after {
   content: "✔";
   position: absolute;
   font-size: 90%;
   left: 0.0625em;
   top: -0.25em;
 }
 </style>
     <!-- Required meta tags -->
     <meta charset="utf-8">
     <meta name="viewport" content="width=device-width, initial-scale=1">
     <!--favicon-->
   <link rel="icon" href="{{url('vertical/assets/images/favicon-32x32.png')}}" type="image/png" />
     <!--plugins-->
     <link rel="stylesheet" href="{{url('vertical/assets/plugins/notifications/css/lobibox.min.css')}}" />
     <meta name="google" content="notranslate">
     <style>
/* Hide the top Google Translate banner */
body > .goog-te-banner-frame.skiptranslate {
    display: none !important;
}

/* Remove spacing left behind */
body {
    top: 0px !important;
}

/* Hide Google Translate tooltip */
.goog-tooltip {
    display: none !important;
}

/* Hide the frame if it's added */
.goog-te-balloon-frame {
    display: none !important;
}
.skiptranslate > iframe.skiptranslate {
    display: none !important;
}

</style>

     <link href="{{url('vertical/assets/plugins/simplebar/css/simplebar.css')}}" rel="stylesheet" />
     <link href="{{url('vertical/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css')}}" rel="stylesheet" />
     <link href="{{url('vertical/assets/plugins/metismenu/css/metisMenu.min.css')}}" rel="stylesheet" />
     <link href="{{url('vertical/assets/plugins/datatable/css/dataTables.bootstrap5.min.css')}}" rel="stylesheet" />
     <!-- loader-->
     <link href="{{url('vertical/assets/css/pace.min.css')}}" rel="stylesheet" />
     <script src="{{url('vertical/assets/js/pace.min.js')}}"></script>
     <!-- Bootstrap CSS -->
     <link href="{{url('vertical/assets/css/bootstrap.min.css')}}" rel="stylesheet">
     <link href="{{url('vertical/assets/css/bootstrap-extended.css')}}" rel="stylesheet">
      <link href="{{url('vertical/assets/css/app.css')}}" rel="stylesheet">
   <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&amp;display=swap" rel="stylesheet">
     <link href="{{url('vertical/assets/css/icons.css')}}" rel="stylesheet">
     <!-- Theme Style CSS -->
     <link rel="stylesheet" href="{{url('vertical/assets/css/dark-theme.css')}}" />
     <link rel="stylesheet" href="{{url('vertical/assets/css/semi-dark.css')}}" />
     <link rel="stylesheet" href="{{url('vertical/assets/css/header-colors.css')}}" />
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
 <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
 <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
 <script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: 'en',   // Change 'en' to your default language
    includedLanguages: 'pt,it,hi,en', // Add more if needed
    layout: google.translate.TranslateElement.InlineLayout.SIMPLE
  }, 'google_translate_element');
}
</script>

<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

   @yield('css')
     <title>@yield('title') </title>
 </head>
 
 <body>
     <?php
     $uid=Auth::user()->id;
     if(isset($_POST['reset_password'])){
     $password=$_POST['password'];
     $newpassword=$_POST['newpassword'];
         $newpassword_hased=Hash::make($newpassword);
         if(Hash::check($password,Auth::user()->password)){
             
             $up=DB::update("update users set password='$newpassword_hased' where id='$uid'  ");
              ?>
                             <script type="text/javascript">
         window.onload = function() {
     success_noti2("Password changed!");
 };
 </script>
              <?php
         }else{
         $password_wrong_err=1;	
     
         }
     }
     ?>
     <form method="post" action="{{url('home')}}">
                         @csrf
 <div class="modal fade" id="resetpassword" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="false">
   <div class="modal-dialog" role="document">
     <div class="modal-content">
       <div class="modal-header">
         <h5 class="modal-title" id="exampleModalLabel">Reset Password</h5>
         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
           <span aria-hidden="true">&times;</span>
         </button>
       </div>
       <div class="modal-body">
           @if(isset($password_wrong_err))
           <div class="alert alert-danger">
               <strong>Sorry!</strong> Password that you entered is wrong
           </div>
                
     <script>
         
         document.getElementById("resetpassword_btn_wrong").click();
     </script>
      
           @endif
          <div class="form-group">
     <label for="exampleInputEmail1">Email address</label>
     <input type="email" class="form-control" id="exampleInputEmail1" name="email" readonly value="{{Auth::user()->email}}" aria-describedby="emailHelp" placeholder="Enter email">
    </div>
   <div class="form-group">
     <label for="exampleInputPassword1">Password</label>
     <input type="password" class="form-control" id="exampleInputPassword1" name="password" placeholder="Password" required>
   </div>
           <hr>
             <div class="form-group">
     <label for="newpassword">New Password</label>
     <input type="text" class="form-control" id="newpassword" name="newpassword" placeholder="New Password" required>
   </div>
       </div>
       <div class="modal-footer">
         <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
         <button type="submit" class="btn btn-primary" name="reset_password">Save changes</button>
       </div>
     </div>
   </div>
                         </div></form>
     <!--wrapper-->
     <div class="wrapper">
         <!--sidebar wrapper -->
         <div class="sidebar-wrapper" data-simplebar="true">
             <div class="sidebar-header">
                 <div>
                 <!--	<img src="{{url('vertical/assets/images/logo-icon.png')}}" class="logo-icon" alt="logo icon"> -->
                 </div>
                 <div>
                   <h4 class="logo-text">Admin</h4>
                 </div>
                 <div class="toggle-icon ms-auto"><i class='bx bx-arrow-to-left'></i>
                 </div>
             </div>
             <!--navigation-->
             <ul class="metismenu" id="menu">
 
         <li>
                     <a href="{{url('home')}}" >
                         <div class="parent-icon 	active"><i class="bx bx-home-circle"></i>
                         </div>
                         <div class="menu-title">Dashboard</div>
                     </a>
                 </li>
 
                 <li>
                     <a href="javascript:;" class="has-arrow">
                         <div class="parent-icon"><i class="bx bx-user-circle"></i>
                         </div>
                         <div class="menu-title">Users</div>
                     </a>
                     <ul>
                     
                  
                     <li> <a href="{{url('admin/users')}}">
                     <i class="bx bx-right-arrow-alt"></i>All</a></li> 

      </ul>
                 </li>
                       
      <li>
                     <a href="{{url('admin/events')}}" >
                         <div class="parent-icon 	active"><i class="bx bx-list-ol"></i>
                         </div>
                         <div class="menu-title">Events</div>
                     </a>
                 </li>
                       <li>
                     <a href="{{url('admin/events_participation')}}" >
                         <div class="parent-icon 	active"><i class="bx bx-list-ol"></i>
                         </div>
                         <div class="menu-title">Events Participation</div>
                     </a>
                 </li>
              
          
              
          
               
            <!-- <li> <a href="{{url('admin/shopping?shop=1&lss_subscription=1')}}"><i class="bx bx-right-arrow-alt"></i>Renew Software</a>
             </li> 
             <li> <a href="{{url('admin/shopping?orders=1')}}"><i class="bx bx-right-arrow-alt"></i>Order History</a>
             </li>-->  
           </ul>
         </li>   
                   
             </ul>
             <!--end navigation-->
         </div>
         <!--end sidebar wrapper -->
         <!--start header -->
         <header>
             <div class="topbar d-flex align-items-center">
                 <nav class="navbar navbar-expand">
                     <div class="mobile-toggle-menu"><i class='bx bx-menu'></i>
                     </div>
                     <div class="search-bar flex-grow-1">
                         <div class="position-relative search-bar-box">
                             <input type="text" class="form-control search-control" placeholder="Type to search..."> <span class="position-absolute top-50 search-show translate-middle-y"><i class='bx bx-search'></i></span>
                             <span class="position-absolute top-50 search-close translate-middle-y"><i class='bx bx-x'></i></span>
                         </div>
                     </div>
                     <div class="top-menu ms-auto">
                         <ul class="navbar-nav align-items-center">
                             <li class="nav-item mobile-search-icon">
                                 <a class="nav-link" href="#">	<i class='bx bx-search'></i>
                                 </a>
                             </li>
                           <li>
     
                               
                   
                         <!-- <li><div id="google_translate_element"></div>
                         </li> -->
                      <li class="nav-item dropdown dropdown-large" style="display:none">
                                 <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                   <!--<span class="alert-count">0</span>-->
                                     <i class='bx bx-bell'></i>
                                 </a>
                                 <div class="dropdown-menu dropdown-menu-end">
                                     <a href="javascript:;">
                                         <div class="msg-header">
                                             <p class="msg-header-title">Notifications</p>
                                          
                                         </div>
                                     </a>
                                     <div class="header-notifications-list">
                                         <!--<a class="dropdown-item" href="javascript:;">
                                             <div class="d-flex align-items-center">
                                                 <div class="notify bg-light-primary text-primary"><i class="bx bx-group"></i>
                                                 </div>
                                                 <div class="flex-grow-1">
                                                     <h6 class="msg-name">New Customers<span class="msg-time float-end">14 Sec
                                                 ago</span></h6>
                                                     <p class="msg-info">5 new user registered</p>
                                                 </div>
                                             </div>
                                         </a>-->
                                          
                                     </div>
                                     <a href="javascript:;">
                                         <div class="text-center msg-footer">No Notification found</div>
                                     </a>
                                 </div>
                             </li>
                         <li class="nav-item dropdown dropdown-large" style="display:none">
                                 <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> <span class="alert-count">8</span>
                                     <i class='bx bx-comment'></i>
                                 </a>
                                 <div class="dropdown-menu dropdown-menu-end">
                                     
                                     <div class="header-message-list">
                                          
 
 
 
 
 
 
 
                                     </div>
                                      
                                 </div>
                             </li> 
                         </ul>
                     </div>
 <?php
                     if(isset($_GET['reset_password'])){
                     if(Hash::check($_POST['password'],Auth::user()->password)){
                         
                     }else{
                     ?>
                     <script>
                         alert("Password not match");
                     </script>
                     <?php
                     }
                     }
                     ?>
 <!-- Modal -->
                     
                     <div class="user-box dropdown">
                      
                          <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"> 
          
 
                        <i class='bx bx-user'></i>
 
                      <div class="user-info ps-3">
                                 <p class="user-name mb-0">{{Auth::user()->name}}</p>
                                 
                             </div>
                         </a>
                         <ul class="dropdown-menu dropdown-menu-end">
                           
                             <li><a class="dropdown-item" href="javascript:;" id="resetpassword_btn_wrong" data-toggle="modal" data-target="#resetpassword"><i class='bx bx-key'></i><span>Change Password</span></a>
                             </li>
                               @if(isset($password_wrong_err))
           <div class="alert alert-danger">
               <strong>Sorry!</strong> Password that you entered is wrong
           </div>
                
     <script>
         
         document.getElementById("resetpassword_btn_wrong").click();
     </script>
      
           @endif
                             <li>
                                 <div class="dropdown-divider mb-0"></div>
                             </li>
                             <li><a class="dropdown-item" href="#"  onclick="event.preventDefault();
                                                      document.getElementById('logout-form').submit();"><i class='bx bx-log-out-circle'></i><span>Logout</span></a>
                             </li>
                         </ul>
                     </div>
                 </nav>
             </div>
         </header>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                         @csrf
                                     </form>
         <!--end header -->
         <!--start page wrapper -->
        <div class="page-wrapper"> 
             <div class="page-content"> 
                 
         @yield('content')
             
               </div>
        </div> 
     
         <!--end page wrapper -->
         <!--start overlay-->
         <div class="overlay toggle-icon"></div>
         <!--end overlay-->
         <!--Start Back To Top Button-->
           <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
         <!--End Back To Top Button-->
         
     </div>
     <!--end wrapper-->
     <!--start switcher-->
     <div class="switcher-wrapper">
         <div class="switcher-btn"> <i class='bx bx-cog bx-spin'></i>
         </div>
         <div class="switcher-body">
             <div class="d-flex align-items-center">
                 <h5 class="mb-0 text-uppercase">Theme Customizer</h5>
                 <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
 
             </div>
             <hr/>
                         <!-- <button type="button" class="" onclick="savetheme();" >Save Theme</button> -->
 
             <h6 class="mb-0">Theme Styles</h6>
             <hr/>
             <div class="d-flex align-items-center justify-content-between" onclick="savetheme();">
                 <div class="form-check">
                     <input class="form-check-input" type="radio" name="flexRadioDefault" id="lightmode" checked>
                     <label class="form-check-label" for="lightmode">Light</label>
                 </div>
                 <div class="form-check">
                     <input class="form-check-input" type="radio" name="flexRadioDefault" id="darkmode">
                     <label class="form-check-label" for="darkmode">Dark</label>
                 </div>
                 <div class="form-check">
                     <input class="form-check-input" type="radio" name="flexRadioDefault" id="semidark">
                     <label class="form-check-label" for="semidark">Semi Dark</label>
                 </div>
             </div>
             <hr/>
             <div class="form-check"  onclick="savetheme();">
                 <input class="form-check-input" type="radio" id="minimaltheme" name="flexRadioDefault">
                 <label class="form-check-label" for="minimaltheme">Minimal Theme</label>
             </div>
             <hr/>
             <h6 class="mb-0">Header Colors </h6>
             <hr/>
             <div class="header-colors-indigators"  onclick="savetheme();">
                 <div class="row row-cols-auto g-3">
                     <div class="col">
                         <div class="indigator headercolor1" id="headercolor1"></div>
                     </div>
                     <div class="col">
                         <div class="indigator headercolor2" id="headercolor2"></div>
                     </div>
                     <div class="col">
                         <div class="indigator headercolor3" id="headercolor3"></div>
                     </div>
                     <div class="col">
                         <div class="indigator headercolor4" id="headercolor4"></div>
                     </div>
                     <div class="col">
                         <div class="indigator headercolor5" id="headercolor5"></div>
                     </div>
                     <div class="col">
                         <div class="indigator headercolor6" id="headercolor6"></div>
                     </div>
                     <div class="col">
                         <div class="indigator headercolor7" id="headercolor7"></div>
                     </div>
                     <div class="col">
                         <div class="indigator headercolor8" id="headercolor8"></div>
                     </div>
                 </div>
             </div>
             <hr/>
             <h6 class="mb-0">Sidebar Colors</h6>
             <hr/>
             <div class="header-colors-indigators"  onclick="savetheme();">
                 <div class="row row-cols-auto g-3">
                     <div class="col">
                         <div class="indigator sidebarcolor1" id="sidebarcolor1"></div>
                     </div>
                     <div class="col">
                         <div class="indigator sidebarcolor2" id="sidebarcolor2"></div>
                     </div>
                     <div class="col">
                         <div class="indigator sidebarcolor3" id="sidebarcolor3"></div>
                     </div>
                     <div class="col">
                         <div class="indigator sidebarcolor4" id="sidebarcolor4"></div>
                     </div>
                     <div class="col">
                         <div class="indigator sidebarcolor5" id="sidebarcolor5"></div>
                     </div>
 
                     <div class="col">
                         <div class="indigator sidebarcolor6" id="sidebarcolor6"></div>
                     </div>
                     <div class="col">
                         <div class="indigator sidebarcolor7" id="sidebarcolor7"></div>
                     </div>
                     <div class="col">
                         <div class="indigator sidebarcolor8" id="sidebarcolor8"></div>
                     </div>
 
                 </div>
             </div>
         </div>
     </div>
     <!--end switcher-->
   <!-- Bootstrap JS -->
   <script type="text/javascript">
       function savetheme() {
            htmltag=document.getElementById('htmltag');
              
              var xhttp = new XMLHttpRequest();
 xhttp.onreadystatechange = function() {
     if (this.readyState == 4 && this.status == 200) {
        // Typical action to be performed when the document is ready:
        //alert(xhttp.responseText);
        // alert("Theme chnaged success");
     }
 };
 xhttp.open("GET", "{{url('api/changetheme/')}}/"+htmltag.className, true);
 xhttp.send();
       }
 
   </script>
    @if (session('success'))
                             <script type="text/javascript">
         window.onload = function() {
     success_noti2("{{session('success')}}");
 };
 </script>
                     @endif
                      @if (session('error'))
                         <script type="text/javascript">
         window.onload = function() {
     error_noti2("{{session('error')}}");
 };
 </script>
                     @endif
   <script src="{{url('vertical/assets/js/jquery.min.js')}}"></script>
 
       <script src="{{url('vertical/assets/js/bootstrap.bundle.min.js')}}"></script>
       <!--plugins-->
       <script src="{{url('vertical/assets/plugins/simplebar/js/simplebar.min.js')}}"></script>
       <script src="{{url('vertical/assets/plugins/metismenu/js/metisMenu.min.js')}}"></script>
       <script src="{{url('vertical/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js')}}"></script>
       <script src="{{url('vertical/assets/plugins/datatable/js/jquery.dataTables.min.js')}}"></script>
       <script src="{{url('vertical/assets/plugins/datatable/js/dataTables.bootstrap5.min.js')}}"></script>
         <script src="{{url('vertical/assets/plugins/notifications/js/notifications.min.js')}}"></script>
 <script src="{{url('vertical/assets/plugins/notifications/js/notification-custom-script.js')}}"></script>
 <script src="{{url('vertical/assets/plugins/notifications/js/lobibox.min.js')}}"></script>
     
   
   
       <script src="{{url('vertical/assets/plugins/vectormap/jquery-jvectormap-2.0.2.min.js')}}"></script>
     <script src="{{url('vertical/assets/plugins/vectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
     
   
   
       <script src="{{url('vertical/assets/plugins/chartjs/js/Chart.min.js')}}"></script>
     <script src="{{url('vertical/assets/plugins/chartjs/js/Chart.extension.js')}}"></script>
   
   
 
   
   
 
       <script>
           $(document).ready(function() {
               $('#example').DataTable();
             } );
       </script>
       <script>
           $(document).ready(function() {
               var table = $('#example2').DataTable( {
                   lengthChange: false,
                   buttons: [ 'copy', 'excel', 'pdf', 'print']
               } );
 
               table.buttons().container()
                   .appendTo( '#example2_wrapper .col-md-6:eq(0)' );
           } );
 
       </script>
       <!-- Notification Js -->
     <script src="{{url('vertical/assets/plugins/notifications/js/lobibox.min.js')}}"></script>
     <script src="{{url('vertical/assets/plugins/notifications/js/notifications.min.js')}}"></script>
     <script src="{{('vertical/assets/plugins/notifications/js/notification-custom-script.js')}}"></script>
   
       <!--app JS-->
       <script src="{{url('vertical/assets/js/app.js')}}"></script>
     @yield('js')
     @yield('scripts')
     
 </body>
  
 </html>
  