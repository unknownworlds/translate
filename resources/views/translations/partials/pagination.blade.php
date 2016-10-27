<div class="row text-center" v-show="pagedData.length">
    <div class="col-md-10 col-md-offset-1">
        <nav>
            <ul class="pagination">
                <li :class="{disabled: currentPage == 0}">
                    <a href="#" aria-label="Previous" @click="previousPage()">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
                <li v-for="n in range(numberOfPages)"
                    :class="{active: n == currentPage}"
                    @click="setPage(n)">
                    <a href="#">@{{ n + 1 }}</a>
                </li>
                <li :class="{disabled: currentPage == numberOfPages - 1}">
                    <a href="#" aria-label="Next" @click="nextPage()">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>