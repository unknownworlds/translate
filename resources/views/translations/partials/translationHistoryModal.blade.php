<div class="modal fade" tabindex="-1" role="dialog" id="translatedStringsHistory">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Translation history</h4>
            </div>
            <div class="modal-body">
                <ul class="list-group">
                    <li class="list-group-item clearfix"
                        ng-repeat="string in translatedStringsHistory" style="white-space: pre-line">
                        @{{string.text}}
                    </li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>