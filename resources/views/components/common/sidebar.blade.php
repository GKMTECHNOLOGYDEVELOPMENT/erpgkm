<div :class="{ 'dark text-white-dark': $store.app.semidark }">
    <nav x-data="sidebar"
        class="sidebar fixed min-h-screen h-full top-0 bottom-0 w-[260px] shadow-[5px_0_25px_0_rgba(94,92,154,0.1)] z-50 transition-all duration-300">
        <div class="bg-white dark:bg-[#0e1726] h-full">
            <div class="flex justify-between items-center px-4 py-3">
                <a href="/" class="main-logo flex items-center shrink-0">
                    <img class="w-8 ml-[5px] flex-none" src="/assets/images/auth/profile.png" alt="image" />
                    <span
                        class="text-2xl ltr:ml-1.5 rtl:mr-1.5  font-semibold  align-middle lg:inline dark:text-white-light">GKM
                    </span>
                </a>
                <a href="javascript:;"
                    class="collapse-icon w-8 h-8 rounded-full flex items-center hover:bg-gray-500/10 dark:hover:bg-dark-light/10 dark:text-white-light transition duration-300 rtl:rotate-180"
                    @click="$store.app.toggleSidebar()">
                    <svg class="w-5 h-5 m-auto" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor"
                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
            <ul class="perfect-scrollbar relative font-semibold space-y-0.5 h-[calc(100vh-80px)] overflow-y-auto overflow-x-hidden  p-4 py-0"
                x-data="{ activeDropdown: null }">
                 <li class="menu nav-item">
                    <button type="button" class="nav-link group" :class="{ 'active': activeDropdown === 'dashboard' }"
                        @click="activeDropdown === 'dashboard' ? activeDropdown = null : activeDropdown = 'dashboard'">
                        <div class="flex items-center">

                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M2 12.2039C2 9.91549 2 8.77128 2.5192 7.82274C3.0384 6.87421 3.98695 6.28551 5.88403 5.10813L7.88403 3.86687C9.88939 2.62229 10.8921 2 12 2C13.1079 2 14.1106 2.62229 16.116 3.86687L18.116 5.10812C20.0131 6.28551 20.9616 6.87421 21.4808 7.82274C22 8.77128 22 9.91549 22 12.2039V13.725C22 17.6258 22 19.5763 20.8284 20.7881C19.6569 22 17.7712 22 14 22H10C6.22876 22 4.34315 22 3.17157 20.7881C2 19.5763 2 17.6258 2 13.725V12.2039Z"
                                    fill="currentColor" />
                                <path
                                    d="M9 17.25C8.58579 17.25 8.25 17.5858 8.25 18C8.25 18.4142 8.58579 18.75 9 18.75H15C15.4142 18.75 15.75 18.4142 15.75 18C15.75 17.5858 15.4142 17.25 15 17.25H9Z"
                                    fill="currentColor" />
                            </svg>

                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Dashboard</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'dashboard' }">

                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'dashboard'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="{{ route('index') }}">Administracion</a>
                        </li>
                        <li>
                            <a href="{{ route('almacen') }}">Almacen</a>
                        </li>
                        <li>
                            <a href="{{ route('tickets') }}">Tickets</a>
                        </li>
                        <li>
                            <a href="{{ route('commercial') }}">Comercial</a>
                        </li>
                    </ul>
                </li> 

                 <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">

                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                        fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Apps GKM</span>
                </h2> 

                <li class="nav-item">
                    <ul>
                         <li class="nav-item">
                            <a href="{{ route('apps.chat') }}" class="group">
                                <div class="flex items-center">

                                    <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M10.4036 22.4797L10.6787 22.015C11.1195 21.2703 11.3399 20.8979 11.691 20.6902C12.0422 20.4825 12.5001 20.4678 13.4161 20.4385C14.275 20.4111 14.8523 20.3361 15.3458 20.1317C16.385 19.7012 17.2106 18.8756 17.641 17.8365C17.9639 17.0571 17.9639 16.0691 17.9639 14.093V13.2448C17.9639 10.4683 17.9639 9.08006 17.3389 8.06023C16.9892 7.48958 16.5094 7.0098 15.9388 6.66011C14.919 6.03516 13.5307 6.03516 10.7542 6.03516H8.20964C5.43314 6.03516 4.04489 6.03516 3.02507 6.66011C2.45442 7.0098 1.97464 7.48958 1.62495 8.06023C1 9.08006 1 10.4683 1 13.2448V14.093C1 16.0691 1 17.0571 1.32282 17.8365C1.75326 18.8756 2.57886 19.7012 3.61802 20.1317C4.11158 20.3361 4.68882 20.4111 5.5477 20.4385C6.46368 20.4678 6.92167 20.4825 7.27278 20.6902C7.6239 20.8979 7.84431 21.2703 8.28514 22.015L8.5602 22.4797C8.97002 23.1721 9.9938 23.1721 10.4036 22.4797ZM13.1928 14.5171C13.7783 14.5171 14.253 14.0424 14.253 13.4568C14.253 12.8713 13.7783 12.3966 13.1928 12.3966C12.6072 12.3966 12.1325 12.8713 12.1325 13.4568C12.1325 14.0424 12.6072 14.5171 13.1928 14.5171ZM10.5422 13.4568C10.5422 14.0424 10.0675 14.5171 9.48193 14.5171C8.89637 14.5171 8.42169 14.0424 8.42169 13.4568C8.42169 12.8713 8.89637 12.3966 9.48193 12.3966C10.0675 12.3966 10.5422 12.8713 10.5422 13.4568ZM5.77108 14.5171C6.35664 14.5171 6.83133 14.0424 6.83133 13.4568C6.83133 12.8713 6.35664 12.3966 5.77108 12.3966C5.18553 12.3966 4.71084 12.8713 4.71084 13.4568C4.71084 14.0424 5.18553 14.5171 5.77108 14.5171Z"
                                            fill="currentColor" />
                                        <path opacity="0.5"
                                            d="M15.486 1C16.7529 0.999992 17.7603 0.999986 18.5683 1.07681C19.3967 1.15558 20.0972 1.32069 20.7212 1.70307C21.3632 2.09648 21.9029 2.63623 22.2963 3.27821C22.6787 3.90219 22.8438 4.60265 22.9226 5.43112C22.9994 6.23907 22.9994 7.24658 22.9994 8.51343V9.37869C22.9994 10.2803 22.9994 10.9975 22.9597 11.579C22.9191 12.174 22.8344 12.6848 22.6362 13.1632C22.152 14.3323 21.2232 15.2611 20.0541 15.7453C20.0249 15.7574 19.9955 15.7691 19.966 15.7804C19.8249 15.8343 19.7039 15.8806 19.5978 15.915H17.9477C17.9639 15.416 17.9639 14.8217 17.9639 14.093V13.2448C17.9639 10.4683 17.9639 9.08006 17.3389 8.06023C16.9892 7.48958 16.5094 7.0098 15.9388 6.66011C14.919 6.03516 13.5307 6.03516 10.7542 6.03516H8.20964C7.22423 6.03516 6.41369 6.03516 5.73242 6.06309V4.4127C5.76513 4.29934 5.80995 4.16941 5.86255 4.0169C5.95202 3.75751 6.06509 3.51219 6.20848 3.27821C6.60188 2.63623 7.14163 2.09648 7.78361 1.70307C8.40759 1.32069 9.10805 1.15558 9.93651 1.07681C10.7445 0.999986 11.7519 0.999992 13.0188 1H15.486Z"
                                            fill="currentColor" />
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Chat</span>
                                </div>
                            </a>
                        </li> 
                         <li class="nav-item">
                            <a href="{{ route('apps.mailbox') }}" class="group">
                                <div class="flex items-center">

                                    <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M24 5C24 6.65685 22.6569 8 21 8C19.3431 8 18 6.65685 18 5C18 3.34315 19.3431 2 21 2C22.6569 2 24 3.34315 24 5Z"
                                            fill="currentColor" />
                                        <path
                                            d="M17.2339 7.46394L15.6973 8.74444C14.671 9.59966 13.9585 10.1915 13.357 10.5784C12.7747 10.9529 12.3798 11.0786 12.0002 11.0786C11.6206 11.0786 11.2258 10.9529 10.6435 10.5784C10.0419 10.1915 9.32941 9.59966 8.30315 8.74444L5.92837 6.76546C5.57834 6.47377 5.05812 6.52106 4.76643 6.87109C4.47474 7.22112 4.52204 7.74133 4.87206 8.03302L7.28821 10.0465C8.2632 10.859 9.05344 11.5176 9.75091 11.9661C10.4775 12.4334 11.185 12.7286 12.0002 12.7286C12.8154 12.7286 13.523 12.4334 14.2495 11.9661C14.947 11.5176 15.7372 10.859 16.7122 10.0465L18.3785 8.65795C17.9274 8.33414 17.5388 7.92898 17.2339 7.46394Z"
                                            fill="currentColor" />
                                        <path
                                            d="M18.4538 6.58719C18.7362 6.53653 19.0372 6.63487 19.234 6.87109C19.3965 7.06614 19.4538 7.31403 19.4121 7.54579C19.0244 7.30344 18.696 6.97499 18.4538 6.58719Z"
                                            fill="currentColor" />
                                        <path opacity="0.5"
                                            d="M16.9576 3.02099C16.156 3 15.2437 3 14.2 3H9.8C5.65164 3 3.57746 3 2.28873 4.31802C1 5.63604 1 7.75736 1 12C1 16.2426 1 18.364 2.28873 19.682C3.57746 21 5.65164 21 9.8 21H14.2C18.3484 21 20.4225 21 21.7113 19.682C23 18.364 23 16.2426 23 12C23 10.9326 23 9.99953 22.9795 9.1797C22.3821 9.47943 21.7103 9.64773 21 9.64773C18.5147 9.64773 16.5 7.58722 16.5 5.04545C16.5 4.31904 16.6646 3.63193 16.9576 3.02099Z"
                                            fill="currentColor" />
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Correo</span>
                                </div>
                            </a>
                        </li> 
                         <li class="nav-item">
                            <a href="{{ route('apps.todolist') }}" class="group">
                                <div class="flex items-center">

                                    <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5"
                                            d="M3 10C3 6.22876 3 4.34315 4.17157 3.17157C5.34315 2 7.22876 2 11 2H13C16.7712 2 18.6569 2 19.8284 3.17157C21 4.34315 21 6.22876 21 10V14C21 17.7712 21 19.6569 19.8284 20.8284C18.6569 22 16.7712 22 13 22H11C7.22876 22 5.34315 22 4.17157 20.8284C3 19.6569 3 17.7712 3 14V10Z"
                                            fill="currentColor" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M12 5.25C12.4142 5.25 12.75 5.58579 12.75 6V7.25H14C14.4142 7.25 14.75 7.58579 14.75 8C14.75 8.41421 14.4142 8.75 14 8.75L12.75 8.75L12.75 10C12.75 10.4142 12.4142 10.75 12 10.75C11.5858 10.75 11.25 10.4142 11.25 10L11.25 8.75H9.99997C9.58575 8.75 9.24997 8.41421 9.24997 8C9.24997 7.58579 9.58575 7.25 9.99997 7.25H11.25L11.25 6C11.25 5.58579 11.5858 5.25 12 5.25ZM7.25 14C7.25 13.5858 7.58579 13.25 8 13.25H16C16.4142 13.25 16.75 13.5858 16.75 14C16.75 14.4142 16.4142 14.75 16 14.75H8C7.58579 14.75 7.25 14.4142 7.25 14ZM8.25 18C8.25 17.5858 8.58579 17.25 9 17.25H15C15.4142 17.25 15.75 17.5858 15.75 18C15.75 18.4142 15.4142 18.75 15 18.75H9C8.58579 18.75 8.25 18.4142 8.25 18Z"
                                            fill="currentColor" />
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Lista
                                        Correo</span>
                                </div>
                            </a>
                        </li> 
                         <li class="nav-item">
                            <a href="{{ route('apps.notes') }}" class="group">
                                <div class="flex items-center">

                                    <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5"
                                            d="M3 10C3 6.22876 3 4.34315 4.17157 3.17157C5.34315 2 7.22876 2 11 2H13C16.7712 2 18.6569 2 19.8284 3.17157C21 4.34315 21 6.22876 21 10V14C21 17.7712 21 19.6569 19.8284 20.8284C18.6569 22 16.7712 22 13 22H11C7.22876 22 5.34315 22 4.17157 20.8284C3 19.6569 3 17.7712 3 14V10Z"
                                            fill="currentColor" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M7.25 12C7.25 11.5858 7.58579 11.25 8 11.25H16C16.4142 11.25 16.75 11.5858 16.75 12C16.75 12.4142 16.4142 12.75 16 12.75H8C7.58579 12.75 7.25 12.4142 7.25 12Z"
                                            fill="currentColor" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M7.25 8C7.25 7.58579 7.58579 7.25 8 7.25H16C16.4142 7.25 16.75 7.58579 16.75 8C16.75 8.41421 16.4142 8.75 16 8.75H8C7.58579 8.75 7.25 8.41421 7.25 8Z"
                                            fill="currentColor" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M7.25 16C7.25 15.5858 7.58579 15.25 8 15.25H13C13.4142 15.25 13.75 15.5858 13.75 16C13.75 16.4142 13.4142 16.75 13 16.75H8C7.58579 16.75 7.25 16.4142 7.25 16Z"
                                            fill="currentColor" />
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Notas</span>
                                </div>
                            </a>
                        </li> 
                        <li class="nav-item">
                            <a href="/apps/scrumboard" class="group">
                                <div class="flex items-center">

                                    <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5"
                                            d="M21 15.9983V9.99826C21 7.16983 21 5.75562 20.1213 4.87694C19.3529 4.10856 18.175 4.01211 16 4H8C5.82497 4.01211 4.64706 4.10856 3.87868 4.87694C3 5.75562 3 7.16983 3 9.99826V15.9983C3 18.8267 3 20.2409 3.87868 21.1196C4.75736 21.9983 6.17157 21.9983 9 21.9983H15C17.8284 21.9983 19.2426 21.9983 20.1213 21.1196C21 20.2409 21 18.8267 21 15.9983Z"
                                            fill="currentColor" />
                                        <path
                                            d="M8 3.5C8 2.67157 8.67157 2 9.5 2H14.5C15.3284 2 16 2.67157 16 3.5V4.5C16 5.32843 15.3284 6 14.5 6H9.5C8.67157 6 8 5.32843 8 4.5V3.5Z"
                                            fill="currentColor" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M12 9.25C12.4142 9.25 12.75 9.58579 12.75 10V12.25L15 12.25C15.4142 12.25 15.75 12.5858 15.75 13C15.75 13.4142 15.4142 13.75 15 13.75L12.75 13.75L12.75 16C12.75 16.4142 12.4142 16.75 12 16.75C11.5858 16.75 11.25 16.4142 11.25 16L11.25 13.75H9C8.58579 13.75 8.25 13.4142 8.25 13C8.25 12.5858 8.58579 12.25 9 12.25L11.25 12.25L11.25 10C11.25 9.58579 11.5858 9.25 12 9.25Z"
                                            fill="currentColor" />
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Scrumboard</span>
                                </div>
                            </a>
                        </li> 
                        <li class="nav-item">
                            <a href="{{ route('apps.contacts') }}" class="group">
                                <div class="flex items-center">

                                    <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5"
                                            d="M19.7165 20.3624C21.143 19.5846 22 18.5873 22 17.5C22 16.3475 21.0372 15.2961 19.4537 14.5C17.6226 13.5794 14.9617 13 12 13C9.03833 13 6.37738 13.5794 4.54631 14.5C2.96285 15.2961 2 16.3475 2 17.5C2 18.6525 2.96285 19.7039 4.54631 20.5C6.37738 21.4206 9.03833 22 12 22C15.1066 22 17.8823 21.3625 19.7165 20.3624Z"
                                            fill="currentColor" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M5 8.51464C5 4.9167 8.13401 2 12 2C15.866 2 19 4.9167 19 8.51464C19 12.0844 16.7658 16.2499 13.2801 17.7396C12.4675 18.0868 11.5325 18.0868 10.7199 17.7396C7.23416 16.2499 5 12.0844 5 8.51464ZM12 11C13.1046 11 14 10.1046 14 9C14 7.89543 13.1046 7 12 7C10.8954 7 10 7.89543 10 9C10 10.1046 10.8954 11 12 11Z"
                                            fill="currentColor" />
                                    </svg>

                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Contactos</span>
                                </div>
                            </a>
                        </li> 
                        @php
                            use Illuminate\Support\Facades\Auth;
                        @endphp
                        @if(Auth::check() && Auth::user()->idRol != 6)

                        <li class="menu nav-item">
                            <button type="button" class="nav-link group"
                                :class="{ 'active': activeDropdown === 'invoice' }"
                                @click="activeDropdown === 'invoice' ? activeDropdown = null : activeDropdown = 'invoice'">
                                <div class="flex items-center">

                                    <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd"
                                            d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z"
                                            fill="currentColor" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M12 5.25C12.4142 5.25 12.75 5.58579 12.75 6V6.31673C14.3804 6.60867 15.75 7.83361 15.75 9.5C15.75 9.91421 15.4142 10.25 15 10.25C14.5858 10.25 14.25 9.91421 14.25 9.5C14.25 8.82154 13.6859 8.10339 12.75 7.84748V11.3167C14.3804 11.6087 15.75 12.8336 15.75 14.5C15.75 16.1664 14.3804 17.3913 12.75 17.6833V18C12.75 18.4142 12.4142 18.75 12 18.75C11.5858 18.75 11.25 18.4142 11.25 18V17.6833C9.61957 17.3913 8.25 16.1664 8.25 14.5C8.25 14.0858 8.58579 13.75 9 13.75C9.41421 13.75 9.75 14.0858 9.75 14.5C9.75 15.1785 10.3141 15.8966 11.25 16.1525V12.6833C9.61957 12.3913 8.25 11.1664 8.25 9.5C8.25 7.83361 9.61957 6.60867 11.25 6.31673V6C11.25 5.58579 11.5858 5.25 12 5.25ZM11.25 7.84748C10.3141 8.10339 9.75 8.82154 9.75 9.5C9.75 10.1785 10.3141 10.8966 11.25 11.1525V7.84748ZM14.25 14.5C14.25 13.8215 13.6859 13.1034 12.75 12.8475V16.1525C13.6859 15.8966 14.25 15.1785 14.25 14.5Z"
                                            fill="currentColor" />
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Invoice</span>
                                </div>
                                <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'invoice' }">

                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                            </button>
                            <ul x-cloak x-show="activeDropdown === 'invoice'" x-collapse class="sub-menu text-gray-500">
                                <li>
                                    <a href="/apps/invoice/list">List</a>
                                </li>
                                <li>
                                    <a href="/apps/invoice/preview">Preview</a>
                                </li>
                                <li>
                                    <a href="/apps/invoice/add">Add</a>
                                </li>
                                <li>
                                    <a href="/apps/invoice/edit">Edit</a>
                                </li>
                            </ul>
                        </li> 


                        <li class="nav-item">
                            <a href="{{ route('apps.calendar') }}" class="group">
                                <div class="flex items-center">

                                    <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                        viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M6.94028 2C7.35614 2 7.69326 2.32421 7.69326 2.72414V4.18487C8.36117 4.17241 9.10983 4.17241 9.95219 4.17241H13.9681C14.8104 4.17241 15.5591 4.17241 16.227 4.18487V2.72414C16.227 2.32421 16.5641 2 16.98 2C17.3958 2 17.733 2.32421 17.733 2.72414V4.24894C19.178 4.36022 20.1267 4.63333 20.8236 5.30359C21.5206 5.97385 21.8046 6.88616 21.9203 8.27586L22 9H2.92456H2V8.27586C2.11571 6.88616 2.3997 5.97385 3.09665 5.30359C3.79361 4.63333 4.74226 4.36022 6.1873 4.24894V2.72414C6.1873 2.32421 6.52442 2 6.94028 2Z"
                                            fill="currentColor" />
                                        <path opacity="0.5"
                                            d="M21.9995 14.0001V12.0001C21.9995 11.161 21.9963 9.66527 21.9834 9H2.00917C1.99626 9.66527 1.99953 11.161 1.99953 12.0001V14.0001C1.99953 17.7713 1.99953 19.6569 3.1711 20.8285C4.34267 22.0001 6.22829 22.0001 9.99953 22.0001H13.9995C17.7708 22.0001 19.6564 22.0001 20.828 20.8285C21.9995 19.6569 21.9995 17.7713 21.9995 14.0001Z"
                                            fill="currentColor" />
                                    </svg>
                                    <span
                                        class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Calendario</span>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>

                <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">

                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>Administracion</span>
                </h2>

                <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'components' }"
                        @click="activeDropdown === 'components' ? activeDropdown = null : activeDropdown = 'components'">
                        <div class="flex items-center">

                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8.42229 20.6181C10.1779 21.5395 11.0557 22.0001 12 22.0001V12.0001L2.63802 7.07275C2.62423 7.09491 2.6107 7.11727 2.5974 7.13986C2 8.15436 2 9.41678 2 11.9416V12.0586C2 14.5834 2 15.8459 2.5974 16.8604C3.19479 17.8749 4.27063 18.4395 6.42229 19.5686L8.42229 20.6181Z"
                                    fill="currentColor" />
                                <path opacity="0.7"
                                    d="M17.5774 4.43152L15.5774 3.38197C13.8218 2.46066 12.944 2 11.9997 2C11.0554 2 10.1776 2.46066 8.42197 3.38197L6.42197 4.43152C4.31821 5.53552 3.24291 6.09982 2.6377 7.07264L11.9997 12L21.3617 7.07264C20.7564 6.09982 19.6811 5.53552 17.5774 4.43152Z"
                                    fill="currentColor" />
                                <path opacity="0.5"
                                    d="M21.4026 7.13986C21.3893 7.11727 21.3758 7.09491 21.362 7.07275L12 12.0001V22.0001C12.9443 22.0001 13.8221 21.5395 15.5777 20.6181L17.5777 19.5686C19.7294 18.4395 20.8052 17.8749 21.4026 16.8604C22 15.8459 22 14.5834 22 12.0586V11.9416C22 9.41678 22 8.15436 21.4026 7.13986Z"
                                    fill="currentColor" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Components</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'components' }">

                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'components'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="/components/tabs">Tabs</a>
                        </li>
                        <li>
                            <a href="/components/accordions">Accordions</a>
                        </li>
                        <li>
                            <a href="/components/modals">Modals</a>
                        </li>
                        <li>
                            <a href="/components/cards">Cards</a>
                        </li>
                        <li>
                            <a href="/components/carousel">Carousel</a>
                        </li>
                        <li>
                            <a href="/components/countdown">Countdown</a>
                        </li>
                        <li>
                            <a href="/components/counter">Counter</a>
                        </li>
                        <li>
                            <a href="/components/sweetalert">Sweet Alerts</a>
                        </li>
                        <li>
                            <a href="/components/timeline">Timeline</a>
                        </li>
                        <li>
                            <a href="/components/notifications">Notifications</a>
                        </li>
                        <li>
                            <a href="/components/media-object">Media Object</a>
                        </li>
                        <li>
                            <a href="/components/list-group">List Group</a>
                        </li>
                        <li>
                            <a href="/components/pricing-table">Pricing Tables</a>
                        </li>
                        <li>
                            <a href="/components/lightbox">Lightbox</a>
                        </li>
                    </ul>
                </li>

                <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'asociados' }"
                        @click="activeDropdown === 'asociados' ? activeDropdown = null : activeDropdown = 'asociados'">
                        <div class="flex items-center">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M17.25 6.75a3 3 0 11-6 0 3 3 0 016 0zM12 14.25a7.5 7.5 0 00-6 3.25M12 14.25a7.5 7.5 0 016 3.25M12 14.25v6.75M6.75 6.75a3 3 0 100 6 3 3 0 000-6zm10.5 0a3 3 0 100 6 3 3 0 000-6zM6.75 12.75V21m10.5-8.25v8.25" />
                            </svg>

                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark ">Asociados</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'asociados' }">

                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'asociados'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="{{ route('administracion.cliente-general') }}">Cliente General</a>
                        </li>
                        <li>
                            <a href="{{ route('administracion.tienda') }}">Tienda</a>
                        </li>
                        <li>
                            <a href="{{ route('administracion.cast') }}">Cast</a>
                        </li>
                        <li>
                            <a href="{{ route('administracion.clientes') }}">Clientes</a>
                        </li>
                        <li>
                            <a href="{{ route('administracion.proveedores') }}">Proveedores</a>
                        </li>
                    </ul>
                </li>

                <li class="menu nav-item">
                    <a href="{{ route('asistencias.index') }}" class="nav-link group">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 group-hover:!text-primary">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 3.75h5.25v5.25H3.75V3.75zM14.25 3.75h6v6h-6V3.75zM3.75 14.25h5.25v6H3.75v-6zM14.25 14.25h2.25v2.25h-2.25v2.25h2.25v2.25h2.25v-2.25h2.25v-2.25h-2.25v-2.25h-2.25z" />
                            </svg>

                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">
                                Asistencias
                            </span>
                        </div>
                    </a>
                </li>


                {{-- <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'cotizaciones' }"
                        @click="activeDropdown === 'cotizaciones' ? activeDropdown = null : activeDropdown = 'cotizaciones'">
                        <div class="flex items-center">

                            <!-- Nuevo SVG relacionado con cotizaciones (documento/factura) -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12h6m-6 4h6M9 8h2m4.5 11.25H6.75A2.25 2.25 0 014.5 17.25V5.25A2.25 2.25 0 016.75 3h7.689a2.25 2.25 0 011.591.659l3.561 3.561c.421.421.659.994.659 1.591v8.439a2.25 2.25 0 01-2.25 2.25z" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Cotizaciones</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'cotizaciones' }">

                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'cotizaciones'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="{{ route('cotizaciones.crear-cotizacion') }}">Agregar</a>
                        </li>
                        <li>
                            <a href="/components/accordions">Lista</a>
                        </li>
                    </ul>
                </li> --}}

                <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'elements' }"
                        @click="activeDropdown === 'elements' ? activeDropdown = null : activeDropdown = 'elements'">
                        <div class="flex items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8.73167 5.77133L5.66953 9.91436C4.3848 11.6526 3.74244 12.5217 4.09639 13.205C4.10225 13.2164 4.10829 13.2276 4.1145 13.2387C4.48945 13.9117 5.59888 13.9117 7.81775 13.9117C9.05079 13.9117 9.6673 13.9117 10.054 14.2754L10.074 14.2946L13.946 9.72466L13.926 9.70541C13.5474 9.33386 13.5474 8.74151 13.5474 7.55682V7.24712C13.5474 3.96249 13.5474 2.32018 12.6241 2.03721C11.7007 1.75425 10.711 3.09327 8.73167 5.77133Z"
                                    fill="currentColor"></path>
                                <path opacity="0.5"
                                    d="M10.4527 16.4432L10.4527 16.7528C10.4527 20.0374 10.4527 21.6798 11.376 21.9627C12.2994 22.2457 13.2891 20.9067 15.2685 18.2286L18.3306 14.0856C19.6154 12.3474 20.2577 11.4783 19.9038 10.7949C19.8979 10.7836 19.8919 10.7724 19.8857 10.7613C19.5107 10.0883 18.4013 10.0883 16.1824 10.0883C14.9494 10.0883 14.3329 10.0883 13.9462 9.72461L10.0742 14.2946C10.4528 14.6661 10.4527 15.2585 10.4527 16.4432Z"
                                    fill="currentColor"></path>
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Elements</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'elements' }">

                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'elements'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="/elements/alerts">Alerts</a>
                        </li>
                        <li>
                            <a href="/elements/avatar">Avatar</a>
                        </li>
                        <li>
                            <a href="/elements/badges">Badges</a>
                        </li>
                        <li>
                            <a href="/elements/breadcrumbs">Breadcrumbs</a>
                        </li>
                        <li>
                            <a href="/elements/buttons">Buttons</a>
                        </li>
                        <li>
                            <a href="/elements/buttons-group">Button Groups</a>
                        </li>
                        <li>
                            <a href="/elements/color-library">Color Library</a>
                        </li>
                        <li>
                            <a href="/elements/dropdown">Dropdown</a>
                        </li>
                        <li>
                            <a href="/elements/infobox">Infobox</a>
                        </li>
                        <li>
                            <a href="/elements/jumbotron">Jumbotron</a>
                        </li>
                        <li>
                            <a href="/elements/loader">Loader</a>
                        </li>
                        <li>
                            <a href="/elements/pagination">Pagination</a>
                        </li>
                        <li>
                            <a href="/elements/popovers">Popovers</a>
                        </li>
                        <li>
                            <a href="/elements/progress-bar">Progress Bar</a>
                        </li>
                        <li>
                            <a href="/elements/search">Search</a>
                        </li>
                        <li>
                            <a href="/elements/tooltips">Tooltips</a>
                        </li>
                        <li>
                            <a href="/elements/treeview">Treeview</a>
                        </li>
                        <li>
                            <a href="/elements/typography">Typography</a>
                        </li>
                    </ul>
                </li>

                {{-- <li class="menu nav-item">
                    <a href="/charts" class="nav-link group">
                        <div class="flex items-center">

                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M6.22209 4.60105C6.66665 4.304 7.13344 4.04636 7.6171 3.82976C8.98898 3.21539 9.67491 2.9082 10.5875 3.4994C11.5 4.09061 11.5 5.06041 11.5 7.00001V8.50001C11.5 10.3856 11.5 11.3284 12.0858 11.9142C12.6716 12.5 13.6144 12.5 15.5 12.5H17C18.9396 12.5 19.9094 12.5 20.5006 13.4125C21.0918 14.3251 20.7846 15.011 20.1702 16.3829C19.9536 16.8666 19.696 17.3334 19.399 17.7779C18.3551 19.3402 16.8714 20.5578 15.1355 21.2769C13.3996 21.9959 11.4895 22.184 9.64665 21.8175C7.80383 21.4509 6.11109 20.5461 4.78249 19.2175C3.45389 17.8889 2.5491 16.1962 2.18254 14.3534C1.81598 12.5105 2.00412 10.6004 2.72315 8.86451C3.44218 7.12861 4.65982 5.64492 6.22209 4.60105Z"
                                    fill="currentColor" />
                                <path
                                    d="M21.446 7.06901C20.6342 5.00831 18.9917 3.36579 16.931 2.55398C15.3895 1.94669 14 3.34316 14 5.00002V9.00002C14 9.5523 14.4477 10 15 10H19C20.6569 10 22.0533 8.61055 21.446 7.06901Z"
                                    fill="currentColor" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Charts</span>
                        </div>
                    </a>
                </li> --}}

                <li class="menu nav-item">
                    <a href="{{ route('usuario') }}" class="nav-link group">
                        <div class="flex items-center">

                            <svg xmlns="http://www.w3.org/2000/svg" class="group-hover:!text-primary shrink-0"
                                width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M12 12c2.485 0 4.5-2.015 4.5-4.5S14.485 3 12 3 7.5 5.015 7.5 7.5 9.515 12 12 12z"
                                    fill="currentColor" />
                                <path opacity="0.7"
                                    d="M12 14c-3.314 0-6 2.686-6 6 0 .552.448 1 1 1h10c.552 0 1-.448 1-1 0-3.314-2.686-6-6-6z"
                                    fill="currentColor" />
                            </svg>

                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Usuarios</span>
                        </div>
                    </a>
                </li>

                {{-- <li class="menu nav-item">
                    <a href="{{ route('administracion.compra') }}" class="nav-link group">
                        <div class="flex items-center">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 3h1.5l.749 2.999m0 0L6.75 15.75m0 0a2.25 2.25 0 002.25 2.25h8.25a2.25 2.25 0 002.25-2.25L21.501 6h-17.252m2.251 9.75a.75.75 0 11-1.5 0m13.5 0a.75.75 0 11-1.5 0M6.75 15.75h10.5" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Compras</span>
                        </div>
                    </a>
                </li> --}}

                {{-- <li class="menu nav-item">
                    <a href="/widgets" class="nav-link group">
                        <div class="flex items-center">

                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M13 15.4C13 13.3258 13 12.2887 13.659 11.6444C14.318 11 15.3787 11 17.5 11C19.6213 11 20.682 11 21.341 11.6444C22 12.2887 22 13.3258 22 15.4V17.6C22 19.6742 22 20.7113 21.341 21.3556C20.682 22 19.6213 22 17.5 22C15.3787 22 14.318 22 13.659 21.3556C13 20.7113 13 19.6742 13 17.6V15.4Z"
                                    fill="currentColor" />
                                <path
                                    d="M2 8.6C2 10.6742 2 11.7113 2.65901 12.3556C3.31802 13 4.37868 13 6.5 13C8.62132 13 9.68198 13 10.341 12.3556C11 11.7113 11 10.6742 11 8.6V6.4C11 4.32582 11 3.28873 10.341 2.64437C9.68198 2 8.62132 2 6.5 2C4.37868 2 3.31802 2 2.65901 2.64437C2 3.28873 2 4.32582 2 6.4V8.6Z"
                                    fill="currentColor" />
                                <path
                                    d="M13 5.5C13 4.4128 13 3.8692 13.1713 3.44041C13.3996 2.86867 13.8376 2.41443 14.389 2.17761C14.8024 2 15.3266 2 16.375 2H18.625C19.6734 2 20.1976 2 20.611 2.17761C21.1624 2.41443 21.6004 2.86867 21.8287 3.44041C22 3.8692 22 4.4128 22 5.5C22 6.5872 22 7.1308 21.8287 7.55959C21.6004 8.13133 21.1624 8.58557 20.611 8.82239C20.1976 9 19.6734 9 18.625 9H16.375C15.3266 9 14.8024 9 14.389 8.82239C13.8376 8.58557 13.3996 8.13133 13.1713 7.55959C13 7.1308 13 6.5872 13 5.5Z"
                                    fill="currentColor" />
                                <path opacity="0.5"
                                    d="M2 18.5C2 19.5872 2 20.1308 2.17127 20.5596C2.39963 21.1313 2.83765 21.5856 3.38896 21.8224C3.80245 22 4.32663 22 5.375 22H7.625C8.67337 22 9.19755 22 9.61104 21.8224C10.1624 21.5856 10.6004 21.1313 10.8287 20.5596C11 20.1308 11 19.5872 11 18.5C11 17.4128 11 16.8692 10.8287 16.4404C10.6004 15.8687 10.1624 15.4144 9.61104 15.1776C9.19755 15 8.67337 15 7.625 15H5.375C4.32663 15 3.80245 15 3.38896 15.1776C2.83765 15.4144 2.39963 15.8687 2.17127 16.4404C2 16.8692 2 17.4128 2 18.5Z"
                                    fill="currentColor" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Widgets</span>
                        </div>
                    </a>
                </li> --}}

                {{-- <li class="menu nav-item">
                    <a href="/font-icons" class="nav-link group">
                        <div class="flex items-center">

                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12Z"
                                    fill="currentColor" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 6.75C9.1005 6.75 6.75 9.1005 6.75 12C6.75 14.8995 9.1005 17.25 12 17.25C12.4142 17.25 12.75 17.5858 12.75 18C12.75 18.4142 12.4142 18.75 12 18.75C8.27208 18.75 5.25 15.7279 5.25 12C5.25 8.27208 8.27208 5.25 12 5.25C15.7279 5.25 18.75 8.27208 18.75 12C18.75 12.8103 18.6069 13.5889 18.3439 14.3108C18.211 14.6756 17.9855 14.9732 17.7354 15.204L17.6548 15.2783C16.8451 16.0252 15.6294 16.121 14.7127 15.5099C14.3431 15.2635 14.0557 14.9233 13.8735 14.5325C13.3499 14.9205 12.7017 15.15 12 15.15C10.2603 15.15 8.85 13.7397 8.85 12C8.85 10.2603 10.2603 8.85 12 8.85C13.7397 8.85 15.15 10.2603 15.15 12V13.5241C15.15 13.8206 15.2981 14.0974 15.5448 14.2618C15.8853 14.4888 16.3369 14.4533 16.6377 14.1758L16.7183 14.1015C16.8295 13.9989 16.8991 13.8944 16.9345 13.7973C17.1384 13.2376 17.25 12.6327 17.25 12C17.25 9.1005 14.8995 6.75 12 6.75ZM12 10.35C12.9113 10.35 13.65 11.0887 13.65 12C13.65 12.9113 12.9113 13.65 12 13.65C11.0887 13.65 10.35 12.9113 10.35 12C10.35 11.0887 11.0887 10.35 12 10.35Z"
                                    fill="currentColor" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Font
                                Icons</span>
                        </div>
                    </a>
                </li> --}}

                {{-- <li class="menu nav-item">
                    <a href="/dragndrop" class="nav-link group">
                        <div class="flex items-center">

                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12Z"
                                    fill="currentColor" />
                                <path
                                    d="M13.25 7C13.25 7.41421 13.5858 7.75 14 7.75H15.1893L12.9697 9.96967C12.6768 10.2626 12.6768 10.7374 12.9697 11.0303C13.2626 11.3232 13.7374 11.3232 14.0303 11.0303L16.25 8.81066V10C16.25 10.4142 16.5858 10.75 17 10.75C17.4142 10.75 17.75 10.4142 17.75 10V7C17.75 6.58579 17.4142 6.25 17 6.25H14C13.5858 6.25 13.25 6.58579 13.25 7Z"
                                    fill="currentColor" />
                                <path
                                    d="M11.0303 14.0303C11.3232 13.7374 11.3232 13.2626 11.0303 12.9697C10.7374 12.6768 10.2626 12.6768 9.96967 12.9697L7.75 15.1893V14C7.75 13.5858 7.41421 13.25 7 13.25C6.58579 13.25 6.25 13.5858 6.25 14V17C6.25 17.4142 6.58579 17.75 7 17.75H10C10.4142 17.75 10.75 17.4142 10.75 17C10.75 16.5858 10.4142 16.25 10 16.25H8.81066L11.0303 14.0303Z"
                                    fill="currentColor" />
                                <path
                                    d="M10.75 7C10.75 7.41421 10.4142 7.75 10 7.75H8.81066L11.0303 9.96967C11.3232 10.2626 11.3232 10.7374 11.0303 11.0303C10.7374 11.3232 10.2626 11.3232 9.96967 11.0303L7.75 8.81066V10C7.75 10.4142 7.41421 10.75 7 10.75C6.58579 10.75 6.25 10.4142 6.25 10V7C6.25 6.58579 6.58579 6.25 7 6.25H10C10.4142 6.25 10.75 6.58579 10.75 7Z"
                                    fill="currentColor" />
                                <path
                                    d="M12.9697 14.0303C12.6768 13.7374 12.6768 13.2626 12.9697 12.9697C13.2626 12.6768 13.7374 12.6768 14.0303 12.9697L16.25 15.1893V14C16.25 13.5858 16.5858 13.25 17 13.25C17.4142 13.25 17.75 13.5858 17.75 14V17C17.75 17.4142 17.4142 17.75 17 17.75H14C13.5858 17.75 13.25 17.4142 13.25 17C13.25 16.5858 13.5858 16.25 14 16.25H15.1893L12.9697 14.0303Z"
                                    fill="currentColor" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Drag
                                and Drop</span>
                        </div>
                    </a>
                </li> --}}


                <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">

                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>ALMACEN</span>
                </h2>

                 <li class="menu nav-item">
                    <a href="/tables" class="nav-link group">
                        <div class="flex items-center">

                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M12 22C7.28595 22 4.92893 22 3.46447 20.5355C2 19.0711 2 16.714 2 12C2 7.28595 2 4.92893 3.46447 3.46447C4.92893 2 7.28595 2 12 2C16.714 2 19.0711 2 20.5355 3.46447C22 4.92893 22 7.28595 22 12C22 16.714 22 19.0711 20.5355 20.5355C19.0711 22 16.714 22 12 22Z"
                                    fill="currentColor" />
                                <path
                                    d="M18.75 8C18.75 8.41421 18.4142 8.75 18 8.75H6C5.58579 8.75 5.25 8.41421 5.25 8C5.25 7.58579 5.58579 7.25 6 7.25H18C18.4142 7.25 18.75 7.58579 18.75 8Z"
                                    fill="currentColor" />
                                <path
                                    d="M18.75 12C18.75 12.4142 18.4142 12.75 18 12.75H6C5.58579 12.75 5.25 12.4142 5.25 12C5.25 11.5858 5.58579 11.25 6 11.25H18C18.4142 11.25 18.75 11.5858 18.75 12Z"
                                    fill="currentColor" />
                                <path
                                    d="M18.75 16C18.75 16.4142 18.4142 16.75 18 16.75H6C5.58579 16.75 5.25 16.4142 5.25 16C5.25 15.5858 5.58579 15.25 6 15.25H18C18.4142 15.25 18.75 15.5858 18.75 16Z"
                                    fill="currentColor" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Tables</span>
                        </div>
                    </a>
                </li> 



                <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'datatables' }"
                        @click="activeDropdown === 'datatables' ? activeDropdown = null : activeDropdown = 'datatables'">
                        <div class="flex items-center">

                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M4.97883 9.68508C2.99294 8.89073 2 8.49355 2 8C2 7.50645 2.99294 7.10927 4.97883 6.31492L7.7873 5.19153C9.77318 4.39718 10.7661 4 12 4C13.2339 4 14.2268 4.39718 16.2127 5.19153L19.0212 6.31492C21.0071 7.10927 22 7.50645 22 8C22 8.49355 21.0071 8.89073 19.0212 9.68508L16.2127 10.8085C14.2268 11.6028 13.2339 12 12 12C10.7661 12 9.77318 11.6028 7.7873 10.8085L4.97883 9.68508Z"
                                    fill="currentColor" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M2 8C2 8.49355 2.99294 8.89073 4.97883 9.68508L7.7873 10.8085C9.77318 11.6028 10.7661 12 12 12C13.2339 12 14.2268 11.6028 16.2127 10.8085L19.0212 9.68508C21.0071 8.89073 22 8.49355 22 8C22 7.50645 21.0071 7.10927 19.0212 6.31492L16.2127 5.19153C14.2268 4.39718 13.2339 4 12 4C10.7661 4 9.77318 4.39718 7.7873 5.19153L4.97883 6.31492C2.99294 7.10927 2 7.50645 2 8Z"
                                    fill="currentColor" />
                                <path opacity="0.7"
                                    d="M5.76613 10L4.97883 10.3149C2.99294 11.1093 2 11.5065 2 12C2 12.4935 2.99294 12.8907 4.97883 13.6851L7.7873 14.8085C9.77318 15.6028 10.7661 16 12 16C13.2339 16 14.2268 15.6028 16.2127 14.8085L19.0212 13.6851C21.0071 12.8907 22 12.4935 22 12C22 11.5065 21.0071 11.1093 19.0212 10.3149L18.2339 10L16.2127 10.8085C14.2268 11.6028 13.2339 12 12 12C10.7661 12 9.77318 11.6028 7.7873 10.8085L5.76613 10Z"
                                    fill="currentColor" />
                                <path opacity="0.4"
                                    d="M5.76613 14L4.97883 14.3149C2.99294 15.1093 2 15.5065 2 16C2 16.4935 2.99294 16.8907 4.97883 17.6851L7.7873 18.8085C9.77318 19.6028 10.7661 20 12 20C13.2339 20 14.2268 19.6028 16.2127 18.8085L19.0212 17.6851C21.0071 16.8907 22 16.4935 22 16C22 15.5065 21.0071 15.1093 19.0212 14.3149L18.2339 14L16.2127 14.8085C14.2268 15.6028 13.2339 16 12 16C10.7661 16 9.77318 15.6028 7.7873 14.8085L5.76613 14Z"
                                    fill="currentColor" />
                            </svg>

                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Data
                                Tables</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'datatables' }">

                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'datatables'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="/datatables/basic">Basic</a>
                        </li>
                        <li>
                            <a href="/datatables/advanced">Advanced</a>
                        </li>
                        <li>
                            <a href="/datatables/skin">Skin</a>
                        </li>
                        <li>
                            <a href="/datatables/order-sorting">Order Sorting</a>
                        </li>
                        <li>
                            <a href="/datatables/multi-column">Multi Column</a>
                        </li>
                        <li>
                            <a href="/datatables/multiple-tables">Multiple Tables</a>
                        </li>
                        <li>
                            <a href="/datatables/alt-pagination">Alt. Pagination</a>
                        </li>
                        <li>
                            <a href="/datatables/checkbox">Checkbox</a>
                        </li>
                        <li>
                            <a href="/datatables/range-search">Range Search</a>
                        </li>
                        <li>
                            <a href="/datatables/export">Export</a>
                        </li>
                        <li>
                            <a href="/datatables/sticky-header">Sticky Header</a>
                        </li>
                        <li>
                            <a href="/datatables/clone-header">Clone Header</a>
                        </li>
                        <li>
                            <a href="/datatables/column-chooser">Column Chooser</a>
                        </li>
                    </ul>
                </li>

                 <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'forms' }"
                        @click="activeDropdown === 'forms' ? activeDropdown = null : activeDropdown = 'forms'">
                        <div class="flex items-center">

                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M3 10C3 6.22876 3 4.34315 4.17157 3.17157C5.34315 2 7.22876 2 11 2H13C16.7712 2 18.6569 2 19.8284 3.17157C21 4.34315 21 6.22876 21 10V14C21 17.7712 21 19.6569 19.8284 20.8284C18.6569 22 16.7712 22 13 22H11C7.22876 22 5.34315 22 4.17157 20.8284C3 19.6569 3 17.7712 3 14V10Z"
                                    fill="currentColor" />
                                <path
                                    d="M16.5189 16.5013C16.6939 16.3648 16.8526 16.2061 17.1701 15.8886L21.1275 11.9312C21.2231 11.8356 21.1793 11.6708 21.0515 11.6264C20.5844 11.4644 19.9767 11.1601 19.4083 10.5917C18.8399 10.0233 18.5356 9.41561 18.3736 8.94849C18.3292 8.82066 18.1644 8.77687 18.0688 8.87254L14.1114 12.8299C13.7939 13.1474 13.6352 13.3061 13.4987 13.4811C13.3377 13.6876 13.1996 13.9109 13.087 14.1473C12.9915 14.3476 12.9205 14.5606 12.7786 14.9865L12.5951 15.5368L12.3034 16.4118L12.0299 17.2323C11.9601 17.4419 12.0146 17.6729 12.1708 17.8292C12.3271 17.9854 12.5581 18.0399 12.7677 17.9701L13.5882 17.6966L14.4632 17.4049L15.0135 17.2214L15.0136 17.2214C15.4394 17.0795 15.6524 17.0085 15.8527 16.913C16.0891 16.8004 16.3124 16.6623 16.5189 16.5013Z"
                                    fill="currentColor" />
                                <path
                                    d="M22.3665 10.6922C23.2112 9.84754 23.2112 8.47812 22.3665 7.63348C21.5219 6.78884 20.1525 6.78884 19.3078 7.63348L19.1806 7.76071C19.0578 7.88348 19.0022 8.05496 19.0329 8.22586C19.0522 8.33336 19.0879 8.49053 19.153 8.67807C19.2831 9.05314 19.5288 9.54549 19.9917 10.0083C20.4545 10.4712 20.9469 10.7169 21.3219 10.847C21.5095 10.9121 21.6666 10.9478 21.7741 10.9671C21.945 10.9978 22.1165 10.9422 22.2393 10.8194L22.3665 10.6922Z"
                                    fill="currentColor" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M7.25 9C7.25 8.58579 7.58579 8.25 8 8.25H14.5C14.9142 8.25 15.25 8.58579 15.25 9C15.25 9.41421 14.9142 9.75 14.5 9.75H8C7.58579 9.75 7.25 9.41421 7.25 9ZM7.25 13C7.25 12.5858 7.58579 12.25 8 12.25H11C11.4142 12.25 11.75 12.5858 11.75 13C11.75 13.4142 11.4142 13.75 11 13.75H8C7.58579 13.75 7.25 13.4142 7.25 13ZM7.25 17C7.25 16.5858 7.58579 16.25 8 16.25H9.5C9.91421 16.25 10.25 16.5858 10.25 17C10.25 17.4142 9.91421 17.75 9.5 17.75H8C7.58579 17.75 7.25 17.4142 7.25 17Z"
                                    fill="currentColor" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Forms</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'forms' }">

                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'forms'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="/forms/basic">Basic</a>
                        </li>
                        <li>
                            <a href="/forms/input-group">Input Group</a>
                        </li>
                        <li>
                            <a href="/forms/layouts">Layouts</a>
                        </li>
                        <li>
                            <a href="/forms/validation">Validation</a>
                        </li>
                        <li>
                            <a href="/forms/input-mask">Input Mask</a>
                        </li>
                        <li>
                            <a href="/forms/select2">Select2</a>
                        </li>
                        <li>
                            <a href="/forms/touchspin">TouchSpin</a>
                        </li>
                        <li>
                            <a href="/forms/checkbox-radio">Checkbox & Radio</a>
                        </li>
                        <li>
                            <a href="/forms/switches">Switches</a>
                        </li>
                        <li>
                            <a href="/forms/wizards">Wizards</a>
                        </li>
                        <li>
                            <a href="/forms/file-upload">File Upload</a>
                        </li>
                        <li>
                            <a href="/forms/quill-editor">Quill Editor</a>
                        </li>
                        <li>
                            <a href="/forms/markdown-editor">Markdown Editor</a>
                        </li>
                        <li>
                            <a href="/forms/date-picker">Date & Range Picker</a>
                        </li>
                        <li>
                            <a href="/forms/clipboard">Clipboard</a>
                        </li>
                    </ul>
                </li> 

                <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'caracteristicas' }"
                        @click="activeDropdown === 'caracteristicas' ? activeDropdown = null : activeDropdown = 'caracteristicas'">
                        <div class="flex items-center">

                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <rect x="3" y="4" width="18" height="2" rx="1"
                                    fill="currentColor" />
                                <rect x="3" y="11" width="18" height="2" rx="1"
                                    fill="currentColor" />
                                <rect x="3" y="18" width="18" height="2" rx="1"
                                    fill="currentColor" />
                            </svg>


                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Detalles
                                del Articulo</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'caracteristicas' }">

                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'caracteristicas'" x-collapse
                        class="sub-menu text-gray-500">

                        <li>
                            <a href="{{ route('modelos.index') }}">Modelos</a>
                        </li>
                        <li>
                            <a href="{{ route('marcas.index') }}">Marcas</a>
                        </li>
                        <li>
                            <a href="{{ route('categorias.index') }}">Categoria</a>
                        </li>
                        <li>
                            <a href="{{ route('subcategoria.index') }}">Sub Categoria</a>
                        </li>
                    </ul>
                </li>



                <li class="menu nav-item">
                    <a href="{{ route('almacen.kits.index') }}" class="nav-link group">
                        <div class="flex items-center">

                            <svg class="group-hover:!text-primary shrink-0" xmlns="http://www.w3.org/2000/svg"
                                width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path
                                    d="M4 9L2 12V18C2 19.1046 2.89543 20 4 20H20C21.1046 20 22 19.1046 22 18V12L20 9H4Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M2 12H22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M7 12V7C7 5.89543 7.89543 5 9 5H15C16.1046 5 17 5.89543 17 7V12"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M12 15V18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M10 15H14" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>

                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Kits
                                de Articulos</span>
                        </div>
                    </a>
                </li>


                <!--nuevo--->
                <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'productos' }"
                        @click="activeDropdown === 'productos' ? activeDropdown = null : activeDropdown = 'productos'">
                        <div class="flex items-center">

                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 8V6C6 4.34315 7.34315 3 9 3H15C16.6569 3 18 4.34315 18 6V8"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M4 8H20C20.5304 8 21.0391 8.21071 21.4142 8.58579C21.7893 8.96086 22 9.46957 22 10V18C22 18.5304 21.7893 19.0391 21.4142 19.4142C21.0391 19.7893 20.5304 20 20 20H4C3.46957 20 2.96086 19.7893 2.58579 19.4142C2.21071 19.0391 2 18.5304 2 18V10C2 9.46957 2.21071 8.96086 2.58579 8.58579C2.96086 8.21071 3.46957 8 4 8Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M16 13C16 14.6569 14.6569 16 13 16C11.3431 16 10 14.6569 10 13"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>

                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Articulos</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'productos' }">

                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'productos'" x-collapse class="sub-menu text-gray-500">
                        <li>

                            <a href="{{ route('producto.index') }}">Productos</a>
                        </li>
                        <li> <a href="{{ route('repuestos.index') }}">Repuestos</a></li>
                        <li>
                            <a href="{{ route('suministros.index') }}">Suministros</a>
                        </li>

                        <li>
                            <a href="{{ route('heramientas.index') }}">Heramientas</a>
                        </li>






                    </ul>
                </li>

                {{--
                <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'Repuesto' }"
                        @click="activeDropdown === 'Repuesto' ? activeDropdown = null : activeDropdown = 'Repuesto'">
                    <div class="flex items-center">
    <svg class="group-hover:!text-primary shrink-0" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <!-- Base de la herramienta/repuesto -->
        <path d="M12 5L8 9H6C4.34315 9 3 10.3431 3 12V15C3 16.6569 4.34315 18 6 18H9C10.6569 18 12 16.6569 12 15V13L16 9H18C19.6569 9 21 7.65685 21 6V3" 
              stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        <!-- Detalle tipo engranaje/ajuste -->
        <path d="M16 9L19 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        <path d="M8 9L5 12" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
        <!-- Tornillo/unión -->
        <circle cx="12" cy="12" r="2" stroke="currentColor" stroke-width="1.5"/>
    </svg>
    <span class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Repuestos</span>
</div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'Repuesto' }">

                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'Repuesto'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="{{ route('repuestos.index') }}">Repuestos</a>
                        </li>
                        <li>
                            <a href="{{ route('repuestos.entrada') }}">Entradas</a>
                        </li>
                         <li>
                            <a href="{{ route('repuestos.index') }}">Salidas</a>
                        </li>
                        
                         
                    </ul>
                </li> 




                <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'suministros' }"
                        @click="activeDropdown === 'suministros' ? activeDropdown = null : activeDropdown = 'suministros'">
                        <div class="flex items-center">
<svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
    <!-- Caja con clip y regla -->
    <path d="M4 20V8L12 4L20 8V20H4Z" stroke="currentColor" stroke-width="1.5"/>
    <path d="M8 12H16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
    <path d="M14 8L10 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
</svg>

                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Suministros</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'suministros' }">

                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'suministros'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="{{ route('suministros.index') }}">Suministros</a>
                        </li>
                    </ul>
                </li>



                        <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'heramientas' }"
                        @click="activeDropdown === 'heramientas' ? activeDropdown = null : activeDropdown = 'heramientas'">
                        <div class="flex items-center">

                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 21H6C4.34315 21 3 19.6569 3 18V8C3 6.34315 4.34315 5 6 5H18C19.6569 5 21 6.34315 21 8V18C21 19.6569 19.6569 21 18 21Z" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M15 9H9V12H15V9Z" stroke="currentColor" stroke-width="1.5"/>
                        <path d="M12 5V3" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                    </svg>

                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Heramientas</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'heramientas' }">

                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'heramientas'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="{{ route('heramientas.index') }}">Heramientas</a>
                        </li>
                    </ul>
                </li>

--}}




                <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'ubicaciones' }"
                        @click="activeDropdown === 'ubicaciones' ? activeDropdown = null : activeDropdown = 'ubicaciones'">
                        <div class="flex items-center">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12 2C8.686 2 6 4.686 6 8C6 12.6667 12 21 12 21C12 21 18 12.6667 18 8C18 4.686 15.314 2 12 2Z"
                                    stroke="currentColor" stroke-width="1.5" fill="none" />
                                <circle cx="12" cy="8" r="2" stroke="currentColor" stroke-width="1.5"
                                    fill="none" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Ubicaciones</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'ubicaciones' }">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'ubicaciones'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="{{ route('ubicaciones.index') }}">Ubicaciones</a>
                        </li>
                    </ul>
                </li>








                <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'solicitud' }"
                        @click="activeDropdown === 'solicitud' ? activeDropdown = null : activeDropdown = 'solicitud'">
                        <div class="flex items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 17L9 11L13 15L21 7" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M21 17V7H11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>



                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Solicitudes</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'solicitud' }">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'solicitud'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="{{ route('solicitudarticulo.index') }}">Solicitud de Articulo</a>
                        </li>
                    </ul>
                </li>







                <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'ventas' }"
                        @click="activeDropdown === 'ventas' ? activeDropdown = null : activeDropdown = 'ventas'">
                        <div class="flex items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 17L9 11L13 15L21 7" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M21 17V7H11" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>



                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Ventas</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'ventas' }">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'ventas'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="{{ route('ventas.index') }}">Ventas</a>
                        </li>
                    </ul>
                </li>


                <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'compras' }"
                        @click="activeDropdown === 'compras' ? activeDropdown = null : activeDropdown = 'compras'">
                        <div class="flex items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M6 7L6 6C6 4.34315 7.34315 3 9 3H15C16.6569 3 18 4.34315 18 6V7"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M4 7H20L19 21H5L4 7Z" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M9 11V13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M15 11V13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>




                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Compras</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'compras' }">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'compras'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="{{ route('compras.index') }}">Compras</a>
                        </li>
                    </ul>
                </li>


                <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'devoluciones' }"
                        @click="activeDropdown === 'devoluciones' ? activeDropdown = null : activeDropdown = 'devoluciones'">
                        <div class="flex items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 7L12 2L21 7V17L12 22L3 17V7Z" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M8 12L5 15L8 18" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M5 15H13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>





                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Devoluciones</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'devoluciones' }">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'devoluciones'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="{{ route('devoluciones.index') }}">Devoluciones</a>
                        </li>
                    </ul>
                </li>



                <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'despacho' }"
                        @click="activeDropdown === 'despacho' ? activeDropdown = null : activeDropdown = 'despacho'">
                        <div class="flex items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 6H16V17H3V6Z" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M16 10H20L21.5 13V17H16V10Z" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <circle cx="6.5" cy="18.5" r="1.5" fill="currentColor" />
                                <circle cx="18" cy="18.5" r="1.5" fill="currentColor" />
                            </svg>

                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Despacho</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'despacho' }">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'despacho'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="{{ route('despacho.index') }}">Despacho</a>
                        </li>
                    </ul>
                </li>


                <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'kardex' }"
                        @click="activeDropdown === 'kardex' ? activeDropdown = null : activeDropdown = 'kardex'">
                        <div class="flex items-center">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 7L12 2L21 7V17L12 22L3 17V7Z" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M3 7L12 12L21 7" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M12 12V22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Kardex</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'kardex' }">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'kardex'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="{{ route('kardex.index') }}">Kardex General</a>
                        </li>
                    </ul>
                </li>



                        @endif


                <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">

                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>TICKETS</span>
                </h2>

                <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'users' }"
                        @click="activeDropdown === 'users' ? activeDropdown = null : activeDropdown = 'users'">
                        <div class="flex items-center">

                            <!-- Icono relacionado con Ordenes de Trabajo (Martillo - Trabajo manual) -->
                            <svg class="group-hover:!text-primary shrink-0 w-6 h-6" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 2l6 6-10 10-6-6L16 2z" />
                            </svg>

                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Ordenes
                                de Trabajo</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'users' }">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'users'" x-collapse class="sub-menu text-gray-500">
                        @if(Auth::check() && Auth::user()->idRol != 6)

                        <li>
                            <a href="{{ route('ordenes.smart') }}">Smart-Tv</a>
                        </li>
                        @endif

                        <li>
                            <a href="{{ route('ordenes.helpdesk') }}">HelpDesk</a>
                        </li>
                        {{-- <li>
                            <a href="{{ route('ordenes.helpdesk') }}">Tec.Laboratorio</a>
                        </li>

                        <li>
                            <a href="{{ route('ordenes.helpdesk') }}">Tec.Labor-smart</a>
                        </li> --}}
                    </ul>
                </li>




                
                <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">

                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>AREA COMERCIAL</span>
                </h2>

                <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'seguimiento' }"
                        @click="activeDropdown === 'seguimiento' ? activeDropdown = null : activeDropdown = 'seguimiento'">
                        <div class="flex items-center">

                            <!-- Icono relacionado con Ordenes de Trabajo (Martillo - Trabajo manual) -->
                            <svg class="group-hover:!text-primary shrink-0 w-6 h-6" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24" fill="none" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 2l6 6-10 10-6-6L16 2z" />
                            </svg>

                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Seguimiento Cliente</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'seguimiento' }">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'seguimiento'" x-collapse class="sub-menu text-gray-500">
                        @if(Auth::check() && Auth::user()->idRol != 6)

                        <li>
                            <a href="{{ route('Seguimiento.index') }}">Clientes</a>
                        </li>
                        @endif
                    </ul>
                </li>

                {{-- <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'pages' }"
                        @click="activeDropdown === 'pages' ? activeDropdown = null : activeDropdown = 'pages'">
                        <div class="flex items-center">

                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5" fill-rule="evenodd" clip-rule="evenodd"
                                    d="M14 22H10C6.22876 22 4.34315 22 3.17157 20.8284C2 19.6569 2 17.7712 2 14V10C2 6.22876 2 4.34315 3.17157 3.17157C4.34315 2 6.23869 2 10.0298 2C10.6358 2 11.1214 2 11.53 2.01666C11.5166 2.09659 11.5095 2.17813 11.5092 2.26057L11.5 5.09497C11.4999 6.19207 11.4998 7.16164 11.6049 7.94316C11.7188 8.79028 11.9803 9.63726 12.6716 10.3285C13.3628 11.0198 14.2098 11.2813 15.0569 11.3952C15.8385 11.5003 16.808 11.5002 17.9051 11.5001L18 11.5001H21.9574C22 12.0344 22 12.6901 22 13.5629V14C22 17.7712 22 19.6569 20.8284 20.8284C19.6569 22 17.7712 22 14 22Z"
                                    fill="currentColor" />
                                <path
                                    d="M6 13.75C5.58579 13.75 5.25 14.0858 5.25 14.5C5.25 14.9142 5.58579 15.25 6 15.25H14C14.4142 15.25 14.75 14.9142 14.75 14.5C14.75 14.0858 14.4142 13.75 14 13.75H6Z"
                                    fill="currentColor" />
                                <path
                                    d="M6 17.25C5.58579 17.25 5.25 17.5858 5.25 18C5.25 18.4142 5.58579 18.75 6 18.75H11.5C11.9142 18.75 12.25 18.4142 12.25 18C12.25 17.5858 11.9142 17.25 11.5 17.25H6Z"
                                    fill="currentColor" />
                                <path
                                    d="M11.5092 2.2601L11.5 5.0945C11.4999 6.1916 11.4998 7.16117 11.6049 7.94269C11.7188 8.78981 11.9803 9.6368 12.6716 10.3281C13.3629 11.0193 14.2098 11.2808 15.057 11.3947C15.8385 11.4998 16.808 11.4997 17.9051 11.4996L21.9574 11.4996C21.9698 11.6552 21.9786 11.821 21.9848 11.9995H22C22 11.732 22 11.5983 21.9901 11.4408C21.9335 10.5463 21.5617 9.52125 21.0315 8.79853C20.9382 8.6713 20.8743 8.59493 20.7467 8.44218C19.9542 7.49359 18.911 6.31193 18 5.49953C17.1892 4.77645 16.0787 3.98536 15.1101 3.3385C14.2781 2.78275 13.862 2.50487 13.2915 2.29834C13.1403 2.24359 12.9408 2.18311 12.7846 2.14466C12.4006 2.05013 12.0268 2.01725 11.5 2.00586L11.5092 2.2601Z"
                                    fill="currentColor" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Pages</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'pages' }">

                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'pages'" x-collapse class="sub-menu text-gray-500">
                        <li>
                            <a href="/pages/knowledge-base">Knowledge Base</a>
                        </li>
                        <li>
                            <a href="/pages/contact-us-boxed" target="_blank">Contact Us Boxed</a>
                        </li>
                        <li>
                            <a href="/pages/contact-us-cover" target="_blank">Contact Us Cover</a>
                        </li>
                        <li>
                            <a href="/pages/faq">Faq</a>
                        </li>
                        <li>
                            <a href="/pages/coming-soon-boxed" target="_blank">Coming Soon Boxed</a>
                        </li>
                        <li>
                            <a href="/pages/coming-soon-cover" target="_blank">Coming Soon Cover</a>
                        </li>
                        <li x-data="{ subActive: null }">
                            <button type="button"
                                class="before:bg-gray-300 before:w-[5px] before:h-[5px] before:rounded ltr:before:mr-2 rtl:before:ml-2 dark:text-[#888ea8] hover:bg-gray-100 dark:hover:bg-gray-900"
                                @click="subActive === 'error' ? subActive = null : subActive = 'error'">Error
                                <div class="ltr:ml-auto rtl:mr-auto rtl:rotate-180"
                                    :class="{ '!rotate-90': subActive === 'error' }">

                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path opacity="0.5"
                                            d="M6.25 19C6.25 19.3139 6.44543 19.5946 6.73979 19.7035C7.03415 19.8123 7.36519 19.7264 7.56944 19.4881L13.5694 12.4881C13.8102 12.2073 13.8102 11.7928 13.5694 11.5119L7.56944 4.51194C7.36519 4.27364 7.03415 4.18773 6.73979 4.29662C6.44543 4.40551 6.25 4.68618 6.25 5.00004L6.25 19Z"
                                            fill="currentColor" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M10.5119 19.5695C10.1974 19.2999 10.161 18.8264 10.4306 18.5119L16.0122 12L10.4306 5.48811C10.161 5.17361 10.1974 4.70014 10.5119 4.43057C10.8264 4.161 11.2999 4.19743 11.5695 4.51192L17.5695 11.5119C17.8102 11.7928 17.8102 12.2072 17.5695 12.4881L11.5695 19.4881C11.2999 19.8026 10.8264 19.839 10.5119 19.5695Z"
                                            fill="currentColor" />
                                    </svg>
                                </div>
                            </button>
                            <ul class="sub-menu text-gray-500 ltr:ml-2 rtl:mr-2" x-show="subActive === 'error'"
                                x-collapse>
                                <li>
                                    <a href="/pages/error404" target="_blank">404</a>
                                </li>
                                <li>
                                    <a href="/pages/error500" target="_blank">500</a>
                                </li>
                                <li>
                                    <a href="/pages/error503" target="_blank">503</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="/pages/maintenence" target="_blank">Maintanence</a>
                        </li>
                    </ul>
                </li> --}}
                {{-- <li class="menu nav-item">
                    <button type="button" class="nav-link group"
                        :class="{ 'active': activeDropdown === 'authentication' }"
                        @click="activeDropdown === 'authentication' ? activeDropdown = null : activeDropdown = 'authentication'">
                        <div class="flex items-center">

                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.5"
                                    d="M2 16C2 13.1716 2 11.7574 2.87868 10.8787C3.75736 10 5.17157 10 8 10H16C18.8284 10 20.2426 10 21.1213 10.8787C22 11.7574 22 13.1716 22 16C22 18.8284 22 20.2426 21.1213 21.1213C20.2426 22 18.8284 22 16 22H8C5.17157 22 3.75736 22 2.87868 21.1213C2 20.2426 2 18.8284 2 16Z"
                                    fill="currentColor" />
                                <path
                                    d="M8 17C8.55228 17 9 16.5523 9 16C9 15.4477 8.55228 15 8 15C7.44772 15 7 15.4477 7 16C7 16.5523 7.44772 17 8 17Z"
                                    fill="currentColor" />
                                <path
                                    d="M12 17C12.5523 17 13 16.5523 13 16C13 15.4477 12.5523 15 12 15C11.4477 15 11 15.4477 11 16C11 16.5523 11.4477 17 12 17Z"
                                    fill="currentColor" />
                                <path
                                    d="M17 16C17 16.5523 16.5523 17 16 17C15.4477 17 15 16.5523 15 16C15 15.4477 15.4477 15 16 15C16.5523 15 17 15.4477 17 16Z"
                                    fill="currentColor" />
                                <path
                                    d="M6.75 8C6.75 5.10051 9.10051 2.75 12 2.75C14.8995 2.75 17.25 5.10051 17.25 8V10.0036C17.8174 10.0089 18.3135 10.022 18.75 10.0546V8C18.75 4.27208 15.7279 1.25 12 1.25C8.27208 1.25 5.25 4.27208 5.25 8V10.0546C5.68651 10.022 6.18264 10.0089 6.75 10.0036V8Z"
                                    fill="currentColor" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Authentication</span>
                        </div>
                        <div class="rtl:rotate-180" :class="{ '!rotate-90': activeDropdown === 'authentication' }">

                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    </button>
                    <ul x-cloak x-show="activeDropdown === 'authentication'" x-collapse
                        class="sub-menu text-gray-500">
                        <li>
                            <a href="/auth/boxed-signin" target="_blank">Login Boxed</a>
                        </li>
                        <li>
                            <a href="/auth/boxed-signup" target="_blank">Register Boxed</a>
                        </li>
                        <li>
                            <a href="/auth/boxed-lockscreen" target="_blank">Unlock Boxed</a>
                        </li>
                        <li>
                            <a href="/auth/boxed-password-reset" target="_blank">Recover ID Boxed</a>
                        </li>
                        <li>
                            <a href="/auth/cover-login" target="_blank">Login Cover</a>
                        </li>
                        <li>
                            <a href="/auth/cover-register" target="_blank">Register Cover</a>
                        </li>
                        <li>
                            <a href="/auth/cover-lockscreen" target="_blank">Unlock Cover</a>
                        </li>
                        <li>
                            <a href="/auth/cover-password-reset" target="_blank">Recover ID Cover</a>
                        </li>
                    </ul>
                </li> --}}

                @if(Auth::check() && Auth::user()->idRol != 6)

                <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">

                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>CONFIGURACIÓN</span>
                </h2>

                <li class="menu nav-item">
                    <a href="{{ route('configuracion') }}" class="nav-link group">
                        <div class="flex items-center">

                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M12 2C12.5523 2 13 2.44772 13 3V5.05884C14.1143 5.27789 15.1627 5.73961 16.07 6.41L17.657 4.82322C18.047 4.43317 18.6806 4.43317 19.0706 4.82322L19.1768 4.92941C19.5668 5.31946 19.5668 5.95302 19.1768 6.34307L17.59 7.93C18.2604 8.83734 18.7221 9.88569 18.9412 11H21C21.5523 11 22 11.4477 22 12C22 12.5523 21.5523 13 21 13H18.9412C18.7221 14.1143 18.2604 15.1627 17.59 16.07L19.1768 17.657C19.5668 18.047 19.5668 18.6806 19.1768 19.0706L19.0706 19.1768C18.6806 19.5668 18.047 19.5668 17.657 19.1768L16.07 17.59C15.1627 18.2604 14.1143 18.7221 13 18.9412V21C13 21.5523 12.5523 22 12 22C11.4477 22 11 21.5523 11 21V18.9412C9.88569 18.7221 8.83734 18.2604 7.93 17.59L6.34315 19.1768C5.9531 19.5668 5.31954 19.5668 4.92949 19.1768L4.8233 19.0706C4.43325 18.6806 4.43325 18.047 4.8233 17.657L6.41 16.07C5.73961 15.1627 5.27789 14.1143 5.05884 13H3C2.44772 13 2 12.5523 2 12C2 11.4477 2.44772 11 3 11H5.05884C5.27789 9.88569 5.73961 8.83734 6.41 7.93L4.82322 6.34315C4.43317 5.9531 4.43317 5.31954 4.82322 4.92949L4.92941 4.8233C5.31946 4.43325 5.95302 4.43325 6.34307 4.8233L7.93 6.41C8.83734 5.73961 9.88569 5.27789 11 5.05884V3C11 2.44772 11.4477 2 12 2ZM12 8C9.79086 8 8 9.79086 8 12C8 14.2091 9.79086 16 12 16C14.2091 16 16 14.2091 16 12C16 9.79086 14.2091 8 12 8Z"
                                    fill="currentColor" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Configuración</span>
                        </div>
                    </a>
                </li>

                @endif


                {{-- <h2
                    class="py-3 px-7 flex items-center uppercase font-extrabold bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08] -mx-4 mb-1">

                    <svg class="w-4 h-5 flex-none hidden" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="5" y1="12" x2="19" y2="12"></line>
                    </svg>
                    <span>SUPPORTS</span>
                </h2> --}}

                {{-- <li class="menu nav-item">
                    <a href="https://vristo.sbthemes.com" target="_blank" class="nav-link group">
                        <div class="flex items-center">

                            <svg class="group-hover:!text-primary shrink-0" width="20" height="20"
                                viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M4 4.69434V18.6943C4 20.3512 5.34315 21.6943 7 21.6943H17C18.6569 21.6943 20 20.3512 20 18.6943V8.69434C20 7.03748 18.6569 5.69434 17 5.69434H5C4.44772 5.69434 4 5.24662 4 4.69434ZM7.25 11.6943C7.25 11.2801 7.58579 10.9443 8 10.9443H16C16.4142 10.9443 16.75 11.2801 16.75 11.6943C16.75 12.1085 16.4142 12.4443 16 12.4443H8C7.58579 12.4443 7.25 12.1085 7.25 11.6943ZM7.25 15.1943C7.25 14.7801 7.58579 14.4443 8 14.4443H13.5C13.9142 14.4443 14.25 14.7801 14.25 15.1943C14.25 15.6085 13.9142 15.9443 13.5 15.9443H8C7.58579 15.9443 7.25 15.6085 7.25 15.1943Z"
                                    fill="currentColor" />
                                <path opacity="0.5"
                                    d="M18 4.00038V5.86504C17.6872 5.75449 17.3506 5.69434 17 5.69434H5C4.44772 5.69434 4 5.24662 4 4.69434V4.62329C4 4.09027 4.39193 3.63837 4.91959 3.56299L15.7172 2.02048C16.922 1.84835 18 2.78328 18 4.00038Z"
                                    fill="currentColor" />
                            </svg>
                            <span
                                class="ltr:pl-3 rtl:pr-3 text-black dark:text-[#506690] dark:group-hover:text-white-dark">Documentation</span>
                        </div>
                    </a>
                </li> --}}
            </ul>
        </div>
    </nav>
</div>
<script>
    document.addEventListener("alpine:init", () => {
        Alpine.data("sidebar", () => ({
            init() {
                const selector = document.querySelector('.sidebar ul a[href="' + window.location
                    .pathname + '"]');
                if (selector) {
                    selector.classList.add('active');
                    const ul = selector.closest('ul.sub-menu');
                    if (ul) {
                        let ele = ul.closest('li.menu').querySelectorAll('.nav-link');
                        if (ele) {
                            ele = ele[0];
                            setTimeout(() => {
                                ele.click();
                            });
                        }
                    }
                }
            },
        }));
    });
</script>
