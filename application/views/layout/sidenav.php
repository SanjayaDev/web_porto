	<!--Sidebar-->
	<div class="sidebar transition overlay-scrollbars">
		<div class="logo">
			<h2 style="font-weight: 700;" class="mb-0">DW<span style="font-weight: 500;">Admin</span></h2>
		</div>

		<div class="sidebar-items">
			<div class="accordion" id="sidebar-items">
				<ul>
					<p class="menu">Apps</p>
					<li>
						<a href="<?= base_url("dashboard") ?>" class="items" <?= $menu == "Dashboard" ? "id='active-sidenav'" : FALSE ?>>
							<i class="fas fa-tachometer-alt"></i>
							<span>Dashboard</span>
						</a>
					</li>
					<li>
						<a href="<?= base_url("about_me") ?>" class="items" <?= $menu == "About Me" ? "id='active-sidenav'" : FALSE ?>>
							<i class="fas fa-user"></i>
							<span>About Me</span>
						</a>
					</li>
					<li>
						<a href="<?= base_url("skill_management") ?>" class="items" <?= $menu == "Skill" ? "id='active-sidenav'" : FALSE ?>>
							<i class="fas fa-magic"></i>
							<span>Skill</span>
						</a>
					</li>
					<li>
						<a href="<?= base_url("portofolio_management") ?>" class="items" <?= $menu == "Portofolio" ? "id='active-sidenav'" : FALSE ?>>
							<i class="fab fa-creative-commons-share"></i>
							<span>Portofolio</span>
						</a>
					</li>
					<li>
						<a href="<?= base_url("social_management") ?>" class="items" <?= $menu == "Social" ? "id='active-sidenav'" : FALSE ?>>
							<i class="fas fa-thumbs-up"></i>
							<span>Kontak & Inquiry</span>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<div class="sidebar-overlay"></div>