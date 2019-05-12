<div id="properties-assign-component">
    <div id="properties-assign-to-model-modal" class="modal fade" tabindex="-1" role="dialog"
         aria-labelledby="properties-assign-to-model-modal-title" aria-hidden="true">
    
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                {!! Form::open([
                        'url'  => route('vanilo.property.sync', [$for, $forId]),
                        'method' => 'PUT'
                    ])
                !!}
    
                <div class="modal-header">
                    <h5 class="modal-title" id="properties-assign-to-model-modal">{{ __('Assign Properties') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
    
                <div class="modal-body">
    
                    <table class="table table-condensed table-striped">
                        <tbody>
                        <tr v-for="(assignedProperty, id) in assignedProperties" :id="id">
                            <th class="align-middle">@{{ assignedProperty.property.name }}</th>
                            <td>
                                Property Added
                                <input name="properties[]" type="hidden" :value="assignedProperty.property.id" @change="onPropertyValueChange($event, id)" class="form-control form-control-sm">
                            </td>
                            <td class="align-middle">
                                <i class="zmdi zmdi-close text-danger" style="cursor: pointer" @click="removePropertyValue(id)"></i>
                            </td>
                        </tr>
                        </tbody>
                        <tfoot>
                            <tr class="bg-success">
                                <th class="align-middle">{{ __('Unused properties') }}:</th>
                                <td>
                                    <select v-model="selected" class="form-control form-control-sm">
                                        <option v-for="(unassignedProperty, id) in unassignedProperties" :value="id">
                                            @{{ unassignedProperty.property.name }}
                                        </option>
                                    </select>
                                </td>
                                <td class="align-middle">
                                    <button class="btn btn-light btn-sm" type="button" :disabled="selected == ''"
                                            @click="addSelectedPropertyValue">
                                        {{ __('Use property') }}
                                    </button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-link" data-dismiss="modal">{{ __('Close') }}</button>
                    <button class="btn btn-primary">{{ __('Save properties') }}</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    
    </div>
    
    @section('scripts')
    @parent()
    <script>
        new Vue({
            //el: '#properties-assign-to-model-modal',
            el: '#properties-assign-component',
            data: {
                selected: "",
                adding: {
                    "name": "",
                    "property_id": ""
                },
                assignedProperties: {
                    @foreach($assignments as $property)
                    "{{ $property->id }}": {
                        "value": "{{ $property->id}}",
                        "property": {
                            "id":  "{{ $property->id }}",
                            "name": "{{ $property->name }}"
                        },
                    },
                    @endforeach
                },
                unassignedProperties: {
                    @foreach($properties->keyBy('id')->except($assignments->map(function ($property) {
                            return $property->id;
                    })->all()) as $unassignedProperty)
                    "{{ $unassignedProperty->id }}": {
                        "value": "",
                        "property": {
                            "id": "{{ $unassignedProperty->id }}",
                            "name": "{{ $unassignedProperty->name }}"
                        },
                    },
                    @endforeach
                }
            },
            methods: {
                addSelectedPropertyValue() {
                    if (this.selected && '' !== this.selected) {
                        var property = this.unassignedProperties[this.selected];
                        if (property) {
                            this.assignedProperties[property.property.id] = property;
                            Vue.delete(this.unassignedProperties, property.property.id);
                        }
                    }
                },
                removePropertyValue(propertyId) {
                    var property = this.assignedProperties[propertyId];
                    if (property) {
                        this.unassignedProperties[propertyId] = property;
                        Vue.delete(this.assignedProperties, propertyId)
                    }
                },
                onPropertyValueChange(event, propertyId) {
                    var selected = this.assignedProperties[propertyId].value;
                    if ('+' !== selected) {
                        return;
                    }
    
                    this.adding.name = this.assignedProperties[propertyId].property.name;
                    this.adding.property_id = propertyId;
    
                    var url = "{{ route('vanilo.property_value.create', '@@@') }}";
                    window.open(url.replace('@@@', propertyId), '_blank');
                    //$('#create-property-value').modal('show');
                }
            }
        });
    </script>
    @endsection
    