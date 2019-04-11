<?php

// Home
Breadcrumbs::register('home', function ($breadcrumbs) {
    $breadcrumbs->push('Home', route('home'));
});

// Home > Dashboard
Breadcrumbs::register('dashboard', function ($breadcrumbs) {
	$breadcrumbs->parent('home');
    $breadcrumbs->push('Dashboard', route('dashboard'));
});

// Home > Account
Breadcrumbs::register('account', function ($breadcrumbs, $section) {
	$breadcrumbs->parent('dashboard');
    $breadcrumbs->push('Account', route('account', ['section' => $section]));
});

// Home > Account > Profile
Breadcrumbs::register('profile', function ($breadcrumbs) {
	$breadcrumbs->parent('account', 'profile');
    $breadcrumbs->push('Profile');
});

// Home > Account > Payment
Breadcrumbs::register('payment', function ($breadcrumbs) {
	$breadcrumbs->parent('account', 'payment');
    $breadcrumbs->push('Payment');
});

// Home > Account > Password
Breadcrumbs::register('password', function ($breadcrumbs) {
	$breadcrumbs->parent('account', 'password');
    $breadcrumbs->push('Password');
});

// Home > Account > Settings
Breadcrumbs::register('settings', function ($breadcrumbs) {
	$breadcrumbs->parent('account', 'settings');
    $breadcrumbs->push('Settings');
});

// Home > Plans
Breadcrumbs::register('plans', function ($breadcrumbs) {
	$breadcrumbs->parent('home');
    $breadcrumbs->push('Plans', route('plans'));
});
