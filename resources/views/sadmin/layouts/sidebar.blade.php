<!-- Sidebar -->
<div data-aos="fade-right" data-aos-duration="1000" class="zSidebar"
    data-background="{{ asset('assets/images/sidebar-graphic.png') }}">
    <div class="zSidebar-overlay"></div>
    <!--  -->
    <div class="zSidebar-wrap h-100">
        <!-- Logo -->
        <a href="{{ route('super-admin.dashboard') }}" class="zSidebar-logo">
            <img class="max-h-35" src="{{ getSettingImage('app_logo') }}" alt="{{ getOption('app_name') }}" />
        </a>
        <!-- Menu & Logout -->
        <div class="zSidebar-fixed">
            <ul class="zSidebar-menu" id="sidebarMenu">
                {{-- ── Overview ─────────────────────────────────────────────── --}}
                <li class="zSidebar-label">
                    <span>{{ __('Overview') }}</span>
                </li>
                <li>
                    <a href="{{ route('super-admin.dashboard') }}"
                        class="d-flex align-items-center cg-21 {{ $activeDashboard ?? '' }}">
                        <div class="d-flex">
                            <svg width="20" height="19" viewBox="0 0 20 19" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M10.1757 0.044908C10.0586 0.0406133 9.94141 0.0406133 9.82434 0.044908C9.05153 0.0732586 8.35564 0.336049 7.59263 0.7735C6.84907 1.1998 5.99304 1.82175 4.90689 2.61089L4.84785 2.65379C3.76168 3.44293 2.90563 4.06488 2.27042 4.64031C1.6186 5.23079 1.15363 5.81142 0.887852 6.53765C0.847591 6.64766 0.811378 6.75911 0.779286 6.87178C0.567436 7.61553 0.602321 8.35857 0.78258 9.21941C0.958244 10.0583 1.28523 11.0646 1.70011 12.3415L3.14557 16.7902C3.45713 17.749 4.35067 18.3982 5.35888 18.3982C6.64415 18.3982 7.68608 17.3563 7.68608 16.071V13.7803C7.68608 13.2433 8.12135 12.8081 8.65827 12.8081H11.3417C11.8787 12.8081 12.3139 13.2433 12.3139 13.7803V16.071C12.3139 17.3563 13.3559 18.3982 14.6411 18.3982C15.6493 18.3982 16.5429 17.749 16.8544 16.7902L18.2999 12.3415C18.7148 11.0646 19.0418 10.0583 19.2174 9.21941C19.3977 8.35857 19.4326 7.61553 19.2207 6.87178C19.1886 6.75912 19.1524 6.64766 19.1122 6.53765C18.8464 5.81142 18.3814 5.23079 17.7296 4.64031C17.0944 4.06487 16.2383 3.44293 15.1522 2.65378L15.0931 2.61088C14.007 1.82174 13.1509 1.1998 12.4074 0.7735C11.6444 0.336049 10.9485 0.0732586 10.1757 0.044908ZM9.87017 1.29407C9.9567 1.29089 10.0433 1.29089 10.1298 1.29407C10.626 1.31227 11.1202 1.47641 11.7857 1.85792C12.4638 2.24671 13.2658 2.82832 14.3879 3.64362C15.5101 4.45892 16.311 5.0419 16.8904 5.56671C17.4588 6.08167 17.7677 6.50096 17.9383 6.96724C17.9681 7.04855 17.9948 7.13093 18.0185 7.21421C18.1546 7.69175 18.1512 8.21248 17.994 8.96322C17.8338 9.72832 17.5284 10.6708 17.0998 11.9899L15.6656 16.4039C15.5214 16.8477 15.1078 17.1482 14.6411 17.1482C14.0462 17.1482 13.5639 16.666 13.5639 16.071V13.7803C13.5639 12.553 12.569 11.5581 11.3417 11.5581H8.65827C7.43099 11.5581 6.43608 12.553 6.43608 13.7803V16.071C6.43608 16.666 5.9538 17.1482 5.35888 17.1482C4.8922 17.1482 4.47861 16.8477 4.3344 16.4039L2.90021 11.9899C2.47158 10.6708 2.16625 9.72832 2.00604 8.96322C1.84884 8.21248 1.84545 7.69175 1.98147 7.21421C2.00519 7.13093 2.03195 7.04856 2.06171 6.96724C2.23236 6.50096 2.54118 6.08167 3.10964 5.56671C3.68896 5.0419 4.48993 4.45892 5.61209 3.64362C6.73425 2.82832 7.53621 2.24671 8.21435 1.85792C8.87977 1.47641 9.37397 1.31227 9.87017 1.29407Z"
                                    fill="#7881A4" />
                            </svg>
                        </div>
                        <span class="">{{ __('Dashboard') }}</span>
                    </a>
                </li>

            
                {{-- ── Subscription ─────────────────────────────────────────────── --}}
                <li class="zSidebar-label">
                    <span>{{ __('Subscription') }}</span>
                </li>
                <li>
                    <a href="{{ route('super-admin.packages.index') }}"
                        class="d-flex align-items-center cg-21 {{ $activePackageIndex ?? '' }}">
                        <div class="d-flex {{ isset($activePackageIndex) ? 'active' : 'collapsed' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                fill="none">
                                <path
                                    d="M1.525 4.16792L8.4 0.406203C8.58369 0.304705 8.79013 0.251465 9 0.251465C9.20987 0.251465 9.41631 0.304705 9.6 0.406203L16.475 4.16948C16.6713 4.27691 16.8352 4.43508 16.9496 4.62747C17.0639 4.81987 17.1245 5.03943 17.125 5.26323V12.7351C17.1245 12.9589 17.0639 13.1785 16.9496 13.3709C16.8352 13.5633 16.6713 13.7214 16.475 13.8289L9.6 17.5921C9.41631 17.6936 9.20987 17.7469 9 17.7469C8.79013 17.7469 8.58369 17.6936 8.4 17.5921L1.525 13.8289C1.32866 13.7214 1.16477 13.5633 1.05043 13.3709C0.936094 13.1785 0.875507 12.9589 0.875 12.7351V5.26402C0.875088 5.03981 0.935476 4.81976 1.04984 4.62692C1.16419 4.43407 1.32831 4.27554 1.525 4.16792ZM9 1.49995L2.72344 4.93745L5.04922 6.21089L11.3266 2.77339L9 1.49995ZM9 8.37495L15.2766 4.93745L12.6281 3.48745L6.35156 6.92495L9 8.37495ZM15.875 6.0312L9.625 9.45152V16.1539L15.875 12.7359V6.0312ZM2.125 12.7328L8.375 16.1539V9.45464L5.875 8.08667V10.875C5.875 11.0407 5.80915 11.1997 5.69194 11.3169C5.57473 11.4341 5.41576 11.5 5.25 11.5C5.08424 11.5 4.92527 11.4341 4.80806 11.3169C4.69085 11.1997 4.625 11.0407 4.625 10.875V7.4023L2.125 6.0312V12.732V12.7328Z"
                                    fill="#7881A4" />
                            </svg>
                        </div>
                        <span class="">{{ __('Packages') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('super-admin.packages.user') }}"
                        class="d-flex align-items-center cg-21 {{ $navSubscriptionActiveClass ?? '' }}">
                        <div class="d-flex {{ isset($navSubscriptionActiveClass) ? 'active' : 'collapsed' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"
                                fill="none">
                                <path
                                    d="M5.625 0.625C2.86357 0.625 0.625 2.86357 0.625 5.625V7.1875C0.625 7.53269 0.904822 7.8125 1.25 7.8125C1.59518 7.8125 1.875 7.53269 1.875 7.1875V5.625C1.875 3.55394 3.55394 1.875 5.625 1.875H7.8125C8.15769 1.875 8.4375 1.59518 8.4375 1.25C8.4375 0.904822 8.15769 0.625 7.8125 0.625H5.625Z"
                                    fill="#7881A4" />
                                <path
                                    d="M19.375 10.625C19.375 10.2798 19.0952 10 18.75 10C18.4048 10 18.125 10.2798 18.125 10.625V14.375C18.125 16.4461 16.4461 18.125 14.375 18.125H12.8125C12.4673 18.125 12.1875 18.4048 12.1875 18.75C12.1875 19.0952 12.4673 19.375 12.8125 19.375H14.375C17.1364 19.375 19.375 17.1364 19.375 14.375V10.625Z"
                                    fill="#7881A4" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M9.375 3.125C9.375 1.74429 10.4943 0.625 11.875 0.625H16.875C18.2557 0.625 19.375 1.74429 19.375 3.125V6.875C19.375 8.25572 18.2557 9.375 16.875 9.375H11.875C10.4943 9.375 9.375 8.25572 9.375 6.875V6.5625C9.375 6.21731 9.65481 5.9375 10 5.9375C10.3452 5.9375 10.625 6.21731 10.625 6.5625V6.875C10.625 7.56534 11.1847 8.125 11.875 8.125H16.875C17.5653 8.125 18.125 7.56534 18.125 6.875V3.125C18.125 2.43464 17.5653 1.875 16.875 1.875H16.5625V3.75C16.5625 4.09519 16.2827 4.375 15.9375 4.375H12.8125C12.4673 4.375 12.1875 4.09519 12.1875 3.75V1.875H11.875C11.1847 1.875 10.625 2.43464 10.625 3.125V3.4375C10.625 3.78269 10.3452 4.0625 10 4.0625C9.65481 4.0625 9.375 3.78269 9.375 3.4375V3.125ZM15.3125 1.875H13.4375V3.125H15.3125V1.875Z"
                                    fill="#7881A4" />
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M6.25 6.875C4.69669 6.875 3.4375 8.13419 3.4375 9.6875C3.4375 11.2408 4.69669 12.5 6.25 12.5C7.80331 12.5 9.0625 11.2408 9.0625 9.6875C9.0625 8.13419 7.80331 6.875 6.25 6.875ZM4.6875 9.6875C4.6875 8.82456 5.38706 8.125 6.25 8.125C7.11294 8.125 7.8125 8.82456 7.8125 9.6875C7.8125 10.5504 7.11294 11.25 6.25 11.25C5.38706 11.25 4.6875 10.5504 4.6875 9.6875Z"
                                    fill="#7881A4" />
                                <path
                                    d="M3.125 13.4375C1.74429 13.4375 0.625 14.5568 0.625 15.9375V16.875C0.625 18.2557 1.74429 19.375 3.125 19.375H4.6875C5.03269 19.375 5.3125 19.0952 5.3125 18.75C5.3125 18.4048 5.03269 18.125 4.6875 18.125H3.125C2.43464 18.125 1.875 17.5653 1.875 16.875V15.9375C1.875 15.2472 2.43464 14.6875 3.125 14.6875H9.375C10.0653 14.6875 10.625 15.2472 10.625 15.9375V16.875C10.625 17.5653 10.0653 18.125 9.375 18.125H7.8125C7.46731 18.125 7.1875 18.4048 7.1875 18.75C7.1875 19.0952 7.46731 19.375 7.8125 19.375H9.375C10.7557 19.375 11.875 18.2557 11.875 16.875V15.9375C11.875 14.5568 10.7557 13.4375 9.375 13.4375H3.125Z"
                                    fill="#7881A4" />
                            </svg>
                        </div>
                        <span class="">{{ __('User Packages') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('super-admin.subscriptions.orders') }}"
                        class="d-flex align-items-center cg-21 {{ $activeSubscriptionIndex ?? '' }}">
                        <div
                            class="d-flex superAdmin-allOrder {{ isset($activeSubscriptionIndex) ? 'active' : 'collapsed' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="17" viewBox="0 0 14 17"
                                fill="none">
                                <path
                                    d="M11.1666 1.3335H2.83329C1.91282 1.3335 1.16663 2.07969 1.16663 3.00016V13.8335C1.16663 14.754 1.91282 15.5002 2.83329 15.5002H11.1666C12.0871 15.5002 12.8333 14.754 12.8333 13.8335V3.00016C12.8333 2.07969 12.0871 1.3335 11.1666 1.3335Z"
                                    stroke="#7881A4" stroke-width="1.4" />
                                <path d="M4.5 5.5H9.5M4.5 8.83333H9.5M4.5 12.1667H7.83333" stroke="#7881A4"
                                    stroke-width="1.4" stroke-linecap="round" />
                            </svg>
                        </div>
                        <span class="">{{ __('All Orders') }}</span>
                    </a>
                </li>
            
                {{-- ── Question Bank ─────────────────────────────────────────────── --}}
                <li class="zSidebar-label">
                    <span>{{ __('Question Bank') }}</span>
                </li>
                <li>
                    <a href="{{ route('super-admin.question-bank.classes.index') }}"
                        class="d-flex align-items-center cg-21 {{ request()->routeIs('super-admin.question-bank.classes.*') ? 'active' : '' }}">
                        <div class="d-flex">
                            <i class="fa-solid fa-school text-para-text"></i>
                        </div>
                        <span class="">{{ __('Classes') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('super-admin.question-bank.subjects.index') }}"
                        class="d-flex align-items-center cg-21 {{ request()->routeIs('super-admin.question-bank.subjects.*') ? 'active' : '' }}">
                        <div class="d-flex">
                            <i class="fa-solid fa-book text-para-text"></i>
                        </div>
                        <span class="">{{ __('Subjects') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('super-admin.question-bank.chapters.index') }}"
                        class="d-flex align-items-center cg-21 {{ request()->routeIs('super-admin.question-bank.chapters.*') ? 'active' : '' }}">
                        <div class="d-flex">
                            <i class="fa-solid fa-bookmark text-para-text"></i>
                        </div>
                        <span class="">{{ __('Chapters') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('super-admin.question-bank.topics.index') }}"
                        class="d-flex align-items-center cg-21 {{ request()->routeIs('super-admin.question-bank.topics.*') ? 'active' : '' }}">
                        <div class="d-flex">
                            <i class="fa-solid fa-list text-para-text"></i>
                        </div>
                        <span class="">{{ __('Topics') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('super-admin.question-bank.education-boards.index') }}"
                        class="d-flex align-items-center cg-21 {{ request()->routeIs('super-admin.question-bank.education-boards.*') ? 'active' : '' }}">
                        <div class="d-flex">
                            <i class="fa-solid fa-school text-para-text"></i>
                        </div>
                        <span class="">{{ __('Education Boards') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('super-admin.question-bank.import.index') }}"
                        class="d-flex align-items-center cg-21 {{ request()->routeIs('super-admin.question-bank.import.*') ? 'active' : '' }}">
                        <div class="d-flex">
                            <i class="fa-solid fa-wand-magic-sparkles text-para-text"></i>
                        </div>
                        <span class="">{{ __('Import & AI Generate') }}</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('super-admin.question-bank.questions.index') }}"
                        class="d-flex align-items-center cg-21 {{ request()->routeIs('super-admin.question-bank.questions.*') ? 'active' : '' }}">
                        <div class="d-flex">
                            <i class="fa-solid fa-file-circle-question text-para-text"></i>
                        </div>
                        <span class="">{{ __('Master Question Bank') }}</span>
                    </a>
                </li>
            
                {{-- ── Management ─────────────────────────────────────────────── --}}
                <li class="zSidebar-label">
                    <span>{{ __('Management') }}</span>
                </li>
                <li>
                    <a href="{{ route('super-admin.user.list') }}"
                        class="d-flex align-items-center cg-21 {{ @$activeUserList }}">
                        <div class="d-flex">
                            <i class="fa-solid fa-user-group text-para-text"></i>
                        </div>
                        <span class="">{{ __('Customer List') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('super-admin.roles.index') }}"
                        class="d-flex align-items-center cg-21 {{ $activeRole ?? '' }}">
                        <div class="d-flex">
                            <i class="fa-solid fa-user-shield text-para-text"></i>
                        </div>
                        <span class="">{{ __('Roles & Permission') }}</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('super-admin.staff.index') }}"
                        class="d-flex align-items-center cg-21 {{ @$activeUsers }}">
                        <div class="d-flex">
                            <i class="fa-solid fa-users text-para-text"></i>
                        </div>
                        <span class="">{{ __('Team Member') }}</span>
                    </a>
                </li>



                {{-- ── Configuration ─────────────────────────────────────────────── --}}
                <li class="zSidebar-label">
                    <span>{{ __('Configuration') }}</span>
                </li>
                <li>
                    <a href="{{ route('super-admin.setting.application-settings') }}"
                        class="d-flex align-items-center cg-21 {{ $activeApplicationSetting ?? '' }}">
                        <div class="d-flex {{ isset($activeApplicationSetting) ? 'active' : 'collapsed' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18"
                                fill="none">
                                <path
                                    d="M1.78336 13.1666C1.43079 12.5575 1.15667 11.9062 0.967529 11.2283C1.37866 11.0193 1.72396 10.7006 1.96523 10.3075C2.2065 9.91446 2.33433 9.46232 2.33459 9.00111C2.33485 8.5399 2.20752 8.08762 1.96669 7.69428C1.72586 7.30094 1.38092 6.98189 0.970029 6.77243C1.34649 5.41042 2.06344 4.16665 3.05336 3.15826C3.44012 3.40993 3.88898 3.54965 4.35024 3.56195C4.81151 3.57426 5.26718 3.45866 5.6668 3.22796C6.06642 2.99727 6.39439 2.66047 6.61441 2.25488C6.83442 1.84928 6.9379 1.3907 6.91336 0.929925C8.28186 0.57655 9.71779 0.57741 11.0859 0.932425C11.0618 1.39299 11.1656 1.85124 11.3859 2.25646C11.6061 2.66168 11.9341 2.9981 12.3336 3.22848C12.7331 3.45887 13.1886 3.57424 13.6496 3.56185C14.1107 3.54946 14.5593 3.40978 14.9459 3.15826C15.9346 4.1676 16.6522 5.41056 17.0317 6.77159C16.6206 6.98068 16.2754 7.2994 16.0342 7.69251C15.793 8.08562 15.6652 8.53779 15.6651 8.999C15.6649 9.46021 15.7923 9.91247 16.0332 10.3058C16.2741 10.699 16.6191 11.018 17.03 11.2274C16.6533 12.5895 15.9361 13.8333 14.9459 14.8416C14.5591 14.5899 14.1102 14.4502 13.649 14.4379C13.1877 14.4256 12.732 14.5412 12.3324 14.7719C11.9328 15.0026 11.6048 15.3394 11.3848 15.745C11.1648 16.1506 11.0613 16.6092 11.0859 17.0699C9.71744 17.4236 8.2815 17.423 6.91336 17.0683C6.93746 16.6078 6.83371 16.1496 6.61361 15.7443C6.3935 15.3391 6.0656 15.0027 5.66618 14.7722C5.26676 14.5418 4.81138 14.4263 4.3504 14.4385C3.88943 14.4508 3.44082 14.5903 3.0542 14.8416C2.5614 14.3391 2.13495 13.7763 1.78336 13.1666ZM6.4992 13.3299C7.38727 13.8421 8.05497 14.6641 8.3742 15.6383C8.79003 15.6774 9.20753 15.6783 9.62336 15.6399C9.9423 14.6652 10.61 13.8425 11.4984 13.3299C12.3864 12.8164 13.4328 12.649 14.4367 12.8599C14.6784 12.5199 14.8867 12.1574 15.06 11.7783C14.3765 11.0143 13.9991 10.025 14 8.99993C14 7.94993 14.3917 6.96909 15.0609 6.22159C14.8862 5.84261 14.677 5.48052 14.4359 5.13993C13.4328 5.35018 12.3874 5.18282 11.5 4.66993C10.612 4.15774 9.94432 3.33574 9.62503 2.36159C9.20836 2.32243 8.7917 2.32159 8.37503 2.36076C8.05591 3.33522 7.38821 4.15753 6.50003 4.66993C5.61201 5.18344 4.56559 5.35082 3.5617 5.13993C3.32003 5.47993 3.1117 5.84243 2.9392 6.22159C3.623 6.98539 4.00077 7.97475 4.00003 8.99993C4.00003 10.0499 3.60753 11.0308 2.9392 11.7783C3.1139 12.1572 3.32311 12.5193 3.5642 12.8599C4.56729 12.6497 5.6127 12.817 6.50003 13.3299M9.00003 11.4999C8.33699 11.4999 7.7011 11.2365 7.23226 10.7677C6.76342 10.2989 6.50003 9.66297 6.50003 8.99993C6.50003 8.33688 6.76342 7.701 7.23226 7.23216C7.7011 6.76332 8.33699 6.49993 9.00003 6.49993C9.66307 6.49993 10.299 6.76332 10.7678 7.23216C11.2366 7.701 11.5 8.33688 11.5 8.99993C11.5 9.66297 11.2366 10.2989 10.7678 10.7677C10.299 11.2365 9.66307 11.4999 9.00003 11.4999ZM9.00003 9.83326C9.22104 9.83326 9.433 9.74546 9.58928 9.58918C9.74556 9.4329 9.83336 9.22094 9.83336 8.99993C9.83336 8.77891 9.74556 8.56695 9.58928 8.41067C9.433 8.25439 9.22104 8.16659 9.00003 8.16659C8.77902 8.16659 8.56705 8.25439 8.41077 8.41067C8.25449 8.56695 8.1667 8.77891 8.1667 8.99993C8.1667 9.22094 8.25449 9.4329 8.41077 9.58918C8.56705 9.74546 8.77902 9.83326 9.00003 9.83326Z"
                                    fill="#7881A4" />
                            </svg>
                        </div>
                        <span class="">{{ __('General Settings') }}</span>
                    </a>
                </li>
               
                
                
               
                
               
            
                {{-- ── Frontend ─────────────────────────────────────────────── --}}
                <li class="zSidebar-label">
                    <span>{{ __('Frontend') }}</span>
                </li>
                <li>
                    <a href="{{ route('super-admin.frontend.sections') }}"
                        class="d-flex align-items-center cg-21 {{ (isset($activeFrontendSection) || isset($activeFrontendFeatures) || isset($activeFrontendServices) || isset($activeFrontendCore) || isset($activeFrontendChooseUs) || isset($activeFrontendFaqs) || isset($activeFrontendTestimonials) || isset($activeFrontendAbout) || isset($activeFrontendPolicies)) ? 'active' : '' }}">
                        <div class="d-flex">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3 9H21M3 15H21M9 3V21M15 3V21M3 3H21V21H3V3Z" stroke="#7881A4" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                        <span>{{ __('Frontend Settings') }}</span>
                    </a>
                </li>

                {{-- ── System ─────────────────────────────────────────────── --}}
                <li class="zSidebar-label">
                    <span>{{ __('System') }}</span>
                </li>
                <li>
                    <a href="{{ route('super-admin.file-version-update') }}"
                        class="d-flex align-items-center cg-21 {{ $activeVersionUpdate ?? '' }}">
                        <div class="d-flex {{ $activeVersionUpdate ?? '' }}">
                            <svg width="25" height="25" viewBox="0 0 25 25" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <g clip-path="url(#clip0_953_924)">
                                    <path d="M1.88647 4.98682V10.9868H7.88647" stroke="white" stroke-opacity="0.7"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M23.8865 20.9868V14.9868H17.8865" stroke="white" stroke-opacity="0.7"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M21.3765 9.98689C20.8693 8.55368 20.0073 7.27229 18.871 6.26231C17.7347 5.25233 16.361 4.54666 14.8782 4.21115C13.3954 3.87564 11.8517 3.92123 10.3913 4.34367C8.93085 4.7661 7.60122 5.55161 6.52647 6.62689L1.88647 10.9869M23.8865 14.9869L19.2465 19.3469C18.1717 20.4222 16.8421 21.2077 15.3817 21.6301C13.9212 22.0526 12.3776 22.0981 10.8948 21.7626C9.41194 21.4271 8.03827 20.7215 6.90194 19.7115C5.76561 18.7015 4.90364 17.4201 4.39647 15.9869"
                                        stroke="white" stroke-opacity="0.7" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </g>
                                <defs>
                                    <clipPath id="clip0_953_924">
                                        <rect width="24" height="24" fill="white"
                                            transform="translate(0.886475 0.986816)" />
                                    </clipPath>
                                </defs>
                            </svg>

                        </div>
                        <span class="">{{ __('Version Update') }}</span>
                    </a>
                </li>
                
            </ul>
            <a href="{{ route('logout') }}"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="d-inline-flex align-items-center cg-15 pt-17 pb-30 px-25">
                <p class="fs-15 fw-500 lh-18 text-para-text">{{ __('Logout') }}</p>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </div>
    </div>
</div>