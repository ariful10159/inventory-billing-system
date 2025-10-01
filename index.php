<?php
// ...existing code...
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Management System - Portfolio Types</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <main class="main-content">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="hero-background"></div>
            <div class="hero-content">
                <h1 class="hero-title">M/S Bhai Bhai Traders</h1>
                <p class="hero-subtitle">Premium Sanitary & Building Materials Since 2003</p>
                <p class="hero-description">
                    Your trusted partner for quality sanitary products, building materials, and industrial supplies. 
                    Serving both wholesale and retail customers with excellence for over 20 years.
                </p>

                <!-- Registration Button -->
                <div style="margin: 2rem 0;">
                    <a href="register.php" class="btn btn-primary" style="font-size: 1.2rem; padding: 1rem 2.5rem; border-radius: 30px; box-shadow: 0 4px 16px rgba(102,126,234,0.15);">
                        <i class="fas fa-user-plus"></i> Register Now
                    </a>
                </div>

                <!-- Owner Profile -->
                <div class="owner-profile">
                    <img src="abbo.jpg" alt="Shop Owner & Proprietor" class="owner-img">
                    <div class="owner-info">
                        <h3>Shop Owner & Proprietor</h3>
                        <p>Leading the business with passion and dedication since 2003</p>
                        <p><i class="fas fa-calendar-alt"></i> Established: 2003</p>
                        <p><i class="fas fa-award"></i> 20+ Years of Excellence</p>
                    </div>
                </div>

                <!-- Authorized Dealers Section -->
                <div class="brands-section">
                    <h3 class="brands-title">🏆 Authorized Dealer Of</h3>
                    <div class="brands-grid">
                        <div class="brand-item">
                            <i class="fas fa-industry"></i><br>
                            PRAN RFL Group
                        </div>
                        <div class="brand-item">
                            <i class="fas fa-water"></i><br>
                            Gazi Tanks
                        </div>
                        <div class="brand-item">
                            <i class="fas fa-motorcycle"></i><br>
                            Gazi Motors
                        </div>
                        <div class="brand-item">
                            <i class="fas fa-hammer"></i><br>
                            Holcim Cement
                        </div>
                        <div class="brand-item">
                            <i class="fas fa-plus"></i><br>
                            Many More Brands
                        </div>
                    </div>
                </div>

                <!-- Stats Section -->
                <div class="stats-section">
                    <div class="stat-card">
                        <div class="stat-number">2003</div>
                        <div class="stat-label">Established Year</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">20+</div>
                        <div class="stat-label">Years Experience</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">Happy Customers</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-number">100%</div>
                        <div class="stat-label">Quality Products</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Portfolio Section -->
        <section class="portfolio-section">
            <h2 class="section-title">Our Product Categories & Services</h2>
            
            <!-- Shop Image Section -->
            <div style="text-align: center; margin-bottom: 3rem;">
                <img src="shop.jpg" alt="M/S Bhai Bhai Traders Shop" style="max-width: 100%; height: 300px; object-fit: cover; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
                <p style="margin-top: 1rem; color: #666; font-style: italic;">Our Modern Showroom & Warehouse Facility</p>
            </div>
            
            <div class="portfolio-grid">
                <!-- Sanitary Products -->
                <div class="portfolio-card" onclick="selectPortfolio('sanitary')">
                    <div class="portfolio-icon">
                        <i class="fas fa-bath"></i>
                    </div>
                    <h3 class="portfolio-title">Sanitary Products</h3>
                    <p class="portfolio-description">Premium quality sanitary fittings and bathroom accessories for modern homes and buildings.</p>
                    <ul class="portfolio-features">
                        <li>Bathroom Fittings</li>
                        <li>Water Closets & Basins</li>
                        <li>Taps & Shower Systems</li>
                        <li>Tiles & Ceramics</li>
                        <li>Modern Accessories</li>
                    </ul>
                </div>

                <!-- Building Materials -->
                <div class="portfolio-card" onclick="selectPortfolio('building')">
                    <div class="portfolio-icon">
                        <i class="fas fa-hammer"></i>
                    </div>
                    <h3 class="portfolio-title">Building Materials</h3>
                    <p class="portfolio-description">Complete range of construction materials from trusted brands like Holcim and more.</p>
                    <ul class="portfolio-features">
                        <li>Cement & Concrete</li>
                        <li>Steel & Iron Rods</li>
                        <li>Bricks & Blocks</li>
                        <li>Paints & Adhesives</li>
                        <li>Construction Tools</li>
                    </ul>
                </div>

                <!-- Water Tanks & Motors -->
                <div class="portfolio-card" onclick="selectPortfolio('water')">
                    <div class="portfolio-icon">
                        <i class="fas fa-water"></i>
                    </div>
                    <h3 class="portfolio-title">Water Tanks & Motors</h3>
                    <p class="portfolio-description">Gazi brand water tanks and motors for reliable water storage and supply solutions.</p>
                    <ul class="portfolio-features">
                        <li>Water Storage Tanks</li>
                        <li>Motor Pumps</li>
                        <li>Pipe & Fittings</li>
                        <li>Water Treatment</li>
                        <li>Installation Service</li>
                    </ul>
                </div>

                <!-- PRAN RFL Products -->
                <div class="portfolio-card" onclick="selectPortfolio('pran')">
                    <div class="portfolio-icon">
                        <i class="fas fa-industry"></i>
                    </div>
                    <h3 class="portfolio-title">PRAN RFL Products</h3>
                    <p class="portfolio-description">Authorized dealer of PRAN RFL Group products including plastic items and household goods.</p>
                    <ul class="portfolio-features">
                        <li>Plastic Products</li>
                        <li>Household Items</li>
                        <li>Industrial Supplies</li>
                        <li>Quality Assurance</li>
                        <li>Bulk Orders</li>
                    </ul>
                </div>

                <!-- Wholesale Services -->
                <div class="portfolio-card" onclick="selectPortfolio('wholesale')">
                    <div class="portfolio-icon">
                        <i class="fas fa-warehouse"></i>
                    </div>
                    <h3 class="portfolio-title">Wholesale Services</h3>
                    <p class="portfolio-description">Bulk supply solutions for contractors, builders, and retailers with competitive pricing.</p>
                    <ul class="portfolio-features">
                        <li>Bulk Pricing</li>
                        <li>Contractor Supplies</li>
                        <li>Project Materials</li>
                        <li>Fast Delivery</li>
                        <li>Credit Facilities</li>
                    </ul>
                </div>

                <!-- Retail Sales -->
                <div class="portfolio-card" onclick="selectPortfolio('retail')">
                    <div class="portfolio-icon">
                        <i class="fas fa-store"></i>
                    </div>
                    <h3 class="portfolio-title">Retail Sales</h3>
                    <p class="portfolio-description">Individual customer service with expert advice and quality products for home improvement.</p>
                    <ul class="portfolio-features">
                        <li>Expert Consultation</li>
                        <li>Product Demonstrations</li>
                        <li>Home Delivery</li>
                        <li>After-Sales Service</li>
                        <li>Warranty Support</li>
                    </ul>
                </div>

                <!-- Consultation Services -->
                <div class="portfolio-card" onclick="selectPortfolio('consultation')">
                    <div class="portfolio-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="portfolio-title">Expert Consultation</h3>
                    <p class="portfolio-description">Professional advice and consultation services for construction and renovation projects.</p>
                    <ul class="portfolio-features">
                        <li>Project Planning</li>
                        <li>Material Estimation</li>
                        <li>Quality Guidance</li>
                        <li>Cost Optimization</li>
                        <li>Technical Support</li>
                    </ul>
                </div>

                <!-- Delivery Services -->
                <div class="portfolio-card" onclick="selectPortfolio('delivery')">
                    <div class="portfolio-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3 class="portfolio-title">Delivery Services</h3>
                    <p class="portfolio-description">Fast and reliable delivery services across the region with proper handling and care.</p>
                    <ul class="portfolio-features">
                        <li>Same Day Delivery</li>
                        <li>Bulk Transportation</li>
                        <li>Safe Handling</li>
                        <li>Regional Coverage</li>
                        <li>Tracking System</li>
                    </ul>
                </div>

                <!-- Customer Support -->
                <div class="portfolio-card" onclick="selectPortfolio('support')">
                    <div class="portfolio-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="portfolio-title">Customer Support</h3>
                    <p class="portfolio-description">Dedicated customer support with 20+ years of experience in the industry.</p>
                    <ul class="portfolio-features">
                        <li>24/7 Support</li>
                        <li>Technical Assistance</li>
                        <li>Product Training</li>
                        <li>Warranty Claims</li>
                        <li>Maintenance Tips</li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="cta-section">
            <h2 class="cta-title">Ready to Experience Quality & Excellence?</h2>
            <p>Join hundreds of satisfied customers who trust M/S Bhai Bhai Traders for their construction and renovation needs since 2003.</p>
            <div style="display: flex; justify-content: center; gap: 2rem; margin: 2rem 0; flex-wrap: wrap;">
                <div style="text-align: center; color: white;">
                    <i class="fas fa-phone" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                    <h4>Call Us</h4>
                    <p>+880 XXXX-XXXXXX</p>
                </div>
                <div style="text-align: center; color: white;">
                    <i class="fas fa-map-marker-alt" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                    <h4>Visit Our Shop</h4>
                    <p>Your Trusted Location</p>
                </div>
                <div style="text-align: center; color: white;">
                    <i class="fas fa-clock" style="font-size: 2rem; margin-bottom: 0.5rem;"></i>
                    <h4>Working Hours</h4>
                    <p>Daily: 8 AM - 8 PM</p>
                </div>
            </div>
            <div class="cta-buttons">
                <a href="register.php" class="btn btn-white">Get Quote</a>
                <a href="portfolio-types.php" class="btn btn-outline">View Products</a>
            </div>
        </section>
    </main>

    <script>
        function selectPortfolio(type) {
            // Store selected portfolio type in localStorage
            localStorage.setItem('selectedPortfolio', type);
            
            // Show confirmation and redirect
            if(confirm(`You selected ${type.charAt(0).toUpperCase() + type.slice(1)} business type. Would you like to proceed with registration?`)) {
                window.location.href = 'register.php?portfolio=' + type;
            }
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });

        // Add animation on scroll
        window.addEventListener('scroll', () => {
            const cards = document.querySelectorAll('.portfolio-card');
            cards.forEach(card => {
                const cardTop = card.getBoundingClientRect().top;
                const cardVisible = 150;
                
                if (cardTop < window.innerHeight - cardVisible) {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }
            });
        });

        // Initialize card animations
        document.addEventListener('DOMContentLoaded', () => {
            const cards = document.querySelectorAll('.portfolio-card');
            cards.forEach((card, index) => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(50px)';
                card.style.transition = 'all 0.6s ease';
                
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 100);
            });
        });
    </script>
</body>
</html>