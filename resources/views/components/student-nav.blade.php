    <aside class="left-sidebar" data-sidebarbg="skin5">
        <!-- Sidebar scroll-->
        <div class="scroll-sidebar">
          <!-- Sidebar navigation-->
          <nav class="sidebar-nav">
            <ul id="sidebarnav" class="pt-4">
              <li class="sidebar-item">
                <a
                  class="sidebar-link waves-effect waves-dark sidebar-link"
                  href="{{ url('admin/dashboard') }}"
                  aria-expanded="false"
                  ><i class="mdi mdi-view-dashboard"></i
                  ><span class="hide-menu">Dashboard</span></a
                >
              </li>
              <li class="sidebar-item">
                <a
                  class="sidebar-link waves-effect waves-dark sidebar-link"
                  href="{{ url('result') }}"
                  aria-expanded="false"
                  ><i class="mdi mdi-school"></i><span class="hide-menu">Result</span></a
                >
              </li>

              <li class="sidebar-item p-3">
                <form method="post" action="{{ route('logout') }}">
                  @csrf
                <button
                  class="
                    w-100
                    btn btn-danger
                    d-flex
                    align-items-center
                    text-white
                  "
                  ><i class="mdi mdi-logout font-20 me-2"></i></i>Logout</button
                >
                </form>
              </li>
            </ul>
          </nav>
          <!-- End Sidebar navigation -->
        </div>
        <!-- End Sidebar scroll-->
      </aside>