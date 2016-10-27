<!-- Translation history -->
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
                    <li class="list-group-item clearfix" :class="{'list-group-item-success': string.is_accepted}"
                        v-for="string in translatedStringsHistory">
                        <p>
                            <strong>@{{string.user.name}}</strong> (added @ @{{string.created_at}}<span
                                    v-if="string.deleted_at">, deleted @ @{{string.deleted_at}}</span>)
                        </p>
                        <span style="white-space: pre-line">@{{string.text}}</span>
                    </li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Base string modification -->
<div class="modal fade" tabindex="-1" role="dialog" id="baseStringEditModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">New base string</h4>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="newStringKey">Key</label>
                        <input type="text" class="form-control" id="newStringKey" v-model="manualInputBaseString.key">
                    </div>
                    <div class="form-group">
                        <label for="newStringValue">Value</label>
                        <textarea class="form-control" rows="10" id="newStringValue" v-model="manualInputBaseString.text"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="saveBaseString(manualInputBaseString.id)">Save changes</button>
            </div>
        </div>
    </div>
</div>
