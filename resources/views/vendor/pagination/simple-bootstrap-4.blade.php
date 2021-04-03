@if ($paginator->hasPages())
<nav>
  <ul class="pagination">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
    <li class="paginate_button page-item previous disabled" aria-disabled="true">
      <a class="page-link" rel="prev">Prev</a>
    </li>
    @else
    <li class="paginate_button page-item previous">
      <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Prev</a>
    </li>
    @endif

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
    <li class="paginate_button page-item next">
      <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
    </li>
    @else
    <li class="paginate_button page-item next disabled" aria-disabled="true">
      <a class="page-link" rel="next">Next</a>
    </li>
    @endif
  </ul>
</nav>
@endif