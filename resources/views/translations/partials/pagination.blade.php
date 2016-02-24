<div class="row text-center" ng-if="pagedData.length">
    <div class="col-md-10 col-md-offset-1">
        <nav>
            <ul class="pagination">
                <li ng-class="{disabled: currentPage == 0}">
                    <a href="#" aria-label="Previous" ng-click="previousPage()">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li ng-repeat="n in range(numberOfPages)"
                    ng-class="{active: n == currentPage}"
                    ng-click="setPage(n)">
                    <a href ng-bind="n + 1">1</a>
                </li>
                <li ng-class="{disabled: currentPage == numberOfPages - 1}">
                    <a href="#" aria-label="Next" ng-click="nextPage()">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>