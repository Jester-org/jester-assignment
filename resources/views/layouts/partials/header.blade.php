<header class="header">
    <div>
        <button class="toggle-btn me-2.5" id="toggleSidebar" aria-expanded="false" aria-controls="sidebar">
            <i class="fas fa-bars"></i>
        </button>
        <a title="home" href="{{url('/home')}}"> {{ session()->get('NAME', 'Guest') }} Syst</a>
    </div>
    <div class="icons-container">
        <a title="home" href="{{url('/home')}}"><i class="fas fa-home d-block"></i></a>
        <i class="fas fa-bell"></i>
        <i class="fas fa-envelope"></i>
        <i class="fas fa-cog"></i>
        <i class="fas fa-user text-white cursor-pointer" id="profilePopup" title="profile"></i>
        
        <!-- Modal Profile -->
        <div class="modalProfile position-absolute bg-white rounded shadow-lg" style="width: 240px; top: 50px; right: 0; display: none; z-index: 2010;">
            <!-- Profile Header -->
            <div class="bg-teal-600 bg-gradient-to-r from-teal-600 to-teal-800 py-3 flex justify-center">
                <div class="p-1 rounded-full bg-white">
                    <img src="https://r-jester.github.io/imgs/Rakyruu.png" alt="Profile Image" class="w-[50px] h-[50px] rounded-full border-2 border-teal-500">
                </div>
            </div>            
            
            <!-- Profile Content -->
            <div class="p-3">
                <h2 class="d-block text-center mb-3 text-success font-extrabold text-4xl">{{ session()->get('NAME') }}</h2>
                
                <!-- Profile Actions -->
                <div class="border-top border-bottom py-2 mb-3">
                    <a href="#" class="d-block text-decoration-none text-dark py-2 px-1 rounded hover-bg-light">
                        <i class="fas fa-id-card text-primary me-2"></i> My Profile
                    </a>
                    <a href="#" class="d-block text-decoration-none text-dark py-2 px-1 rounded hover-bg-light">
                        <i class="fas fa-cog text-primary me-2"></i> Settings
                    </a>
                </div>
                
                <!-- Logout Button -->
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-danger w-100 d-flex align-items-center justify-content-center">
                    <i class="fas fa-sign-out-alt me-2"></i> Log Out
                </a>
            </div>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        
        <a title="sign out" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt d-block"></i>
        </a>        
    </div>
</header>

<script type="module">
    $(function() {
        $("#profilePopup").click(function(e) {
            $(".modalProfile").slideToggle("fast");
            e.stopPropagation();
        });
        
        // Close modal when clicking outside
        $(document).click(function(e) {
            if (!$(e.target).closest('.modalProfile').length && !$(e.target).is('#profilePopup')) {
                $(".modalProfile").slideUp("fast");
            }
        });
    });
</script>

<style>
    /* Minimal custom styles that can't be achieved with Bootstrap/Tailwind */
    .cursor-pointer {
        cursor: pointer;
    }
    
    .hover-bg-light:hover {
        background-color: #f8f9fa;
    }
</style>

<style>
    /* Header Styles */
    .header {
        background: #333;
        color: white;
        position: sticky;
        top: 0;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 15px;
        z-index: 2001;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .toggle-btn {
        background: none;
        border: none;
        color: white;
        font-size: 1.2rem;
        cursor: pointer;
        z-index: 2002;
        position: relative;
    }

    .icons-container {
        display: flex;
        gap: 15px;
    }

    .icons-container i {
        cursor: pointer;
    }
</style>
