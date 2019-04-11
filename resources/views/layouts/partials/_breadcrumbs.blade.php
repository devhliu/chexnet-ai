@if (count($breadcrumbs))
  <div class="m-subheader" style="padding: 0px;">
    <div class="d-flex align-items-center">
      <div class="mr-auto">
        <span class="m-subheader__title m-subheader__title--separator">
          {{ ucfirst($breadcrumbs[count($breadcrumbs) - 1]->title) }}
        </span>
        <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
          @foreach ($breadcrumbs as $breadcrumb)
            @if ($breadcrumb->title === 'Home' )
              <li class="m-nav__item m-nav__item--home">
                <a href="{{ $breadcrumb->url }}" class="m-nav__link m-nav__link--icon">
                  <i class="m-nav__link-icon la la-home"></i>
                </a>
              </li>
            @else 
              <li id="breadcrumb" class="m-nav__item">
                <a href="{{ $breadcrumb->url }}" class="m-nav__link">
                  <span class="m-nav__link-text">
                    {{ ucfirst($breadcrumb->title)  }}
                  </span>
                </a>
              </li>
            @endif

            @if ($breadcrumb->url && !$loop->last)
              <li id="breadcrumb" class="m-nav__separator">
                -
              </li>
            @endif
          @endforeach
        </ul>
      </div>

      @if($breadcrumbs[count($breadcrumbs) - 1]->title === 'Profile')
      @endif
    </div>
  </div>
@endif
