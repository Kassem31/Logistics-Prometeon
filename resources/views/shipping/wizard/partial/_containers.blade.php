<table class='table table-bordered' id="containers">
    <thead>
        <tr class="table-active">
            <th class="text-center" style="width:80px">#</th>
            <th class="text-center" style="width:150px;">Container Number</th>
            <th class="text-center" >Container Size</th>
            <td class="text-center">Container Load Type</td>
            <th class='text-center' style='width:100px;'></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($inbound->containers as $container)
            <tr>
                <td class="text-center row_number"></td>
                <td class="text-center">
                    <div>
                        <input type='text' class='form-control container_no' value="{{ old("container_edit.{$container->id}.container_no",$container->container_no) }}"
                        placeholder='Container Number' name='container_edit[{{ $container->id }}][container_no]'>
                    </div>
                </td>
                <td class="text-center">
                    <div>
                        <select class='form-control container_size'
                        title="Select Container Size ..." name='container_edit[{{ $container->id }}][container_size_id]'>
                            <option value="">Select Container Size ...</option>
                            @foreach ($sizes as $item)
                                <option value="{{ $item->id}}" {{ $item->id == old("container_edit.{$container->id}.container_size_id",$container->container_size_id) ? 'selected' : ''}}> {{ $item->size }}</option>
                            @endforeach
                        </select>
                    </div>
                </td>
                <td class="text-center container_load">
                    <div>
                        <select class='form-control'
                        title="Select Container Loading Type ..." name='container_edit[{{ $container->id }}][load_type_id]'>
                        <option value="">Select Container Loading Type ...</option>
                        @foreach ($loadTypes as $item)
                            <option value="{{ $item->id }}" {{ $item->id == old("container_edit.{$container->id}.load_type_id",$container->load_type_id) ? 'selected' : ''}}>{{ $item->name }}</option>
                        @endforeach
                        </select>
                    </div>
                </td>
                <td class="text-center">
                    <div class="kt-checkbox-list">
                        <label class="kt-checkbox kt-checkbox--danger">
                            <input type="checkbox" value="{{ $container->id }}" name="container_delete[]"> Remove
                            <span></span>
                        </label>
                    </div>
                </td>
            </tr>
        @endforeach
        @forelse (old('container',[]) as $key=>$container)
        <tr>
            <td class="text-center row_number"></td>
            <td class="text-center container_number">
                <div>
                    <input type='text' class='form-control container_no @error('container.{{ $key }}.container_no') is-invalid @enderror' value="{{ $container['container_no'] }}"
                     placeholder='Container Number' name='container[{{ $key }}][container_no]'>
                    @error('container.{{ $key }}.container_no')
                        <span class='form-text text-danger'>{{ $message }}</span>
                    @enderror
                </div>
            </td>
            <td class="text-center container_size">
                <div>
                    <select class='form-control container_size @error('container.{{ $key }}.container_size_id') is-invalid @enderror'
                    title="Select Container Size ..." name='container[{{ $key }}][container_size_id]'>
                        <option value="">Select Container Size ...</option>
                        @foreach ($sizes as $item)
                            <option value="{{ $item->id}}" {{ $item->id == $container['container_size_id'] ? 'selected' : ''}}> {{ $item->size }}</option>
                        @endforeach
                    </select>
                    @error('container.{{ $key }}.container_size_id')
                        <span class='form-text text-danger'>{{ $message }}</span>
                    @enderror
                </div>
            </td>
            <td class="text-center container_load">
                <div>
                    <select class='form-control container_load @error('container.{{ $key }}.load_type_id') is-invalid @enderror'
                    title="Select Container Loading Type ..." name='container[{{ $key }}][load_type_id]'>
                    <option value="">Select Container Loading Type ...</option>
                    @foreach ($loadTypes as $item)
                        <option value="{{ $item->id }}" {{ $item->id == $container['load_type_id'] ? 'selected' : ''}}>{{ $item->name }}</option>
                    @endforeach
                    </select>
                    @error('container.{{ $key }}.load_type_id')
                        <span class='form-text text-danger'>{{ $message }}</span>
                    @enderror
                </div>
            </td>
            <td class="text-center row_action">
                <button type="button" class="btn btn-danger btn-icon btn-sm remove-btn"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-warning btn-icon btn-sm add-btn"><i class="fa fa-plus"></i></button>
            </td>
        </tr>
        @empty
        <tr id="containerFirst">
            <td class="text-center row_number"></td>
            <td class="text-center container_number">
                <div>
                    <input type='text' class='form-control container_no @error('container.1.container_no') is-invalid @enderror' placeholder='Container Number' name='container[1][container_no]'>
                    @error('container.1.container_no')
                        <span class='form-text text-danger'>{{ $message }}</span>
                    @enderror
                </div>
            </td>
            <td class="text-center container_size">
                <div>
                    <select class='form-control container_size @error('container.1.container_size_id') is-invalid @enderror'
                    title="Select Container Size ..." name='container[1][container_size_id]'>
                        <option value="">Select Container Size ...</option>
                        @foreach ($sizes as $item)
                            <option value="{{ $item->id}}" > {{ $item->size }}</option>
                        @endforeach
                    </select>
                    @error('container.1.container_size_id')
                        <span class='form-text text-danger'>{{ $message }}</span>
                    @enderror
                </div>
            </td>
            <td class="text-center container_load">
                <div>
                    <select class='form-control container_load @error('container.1.load_type_id') is-invalid @enderror'
                    title="Select Container Loading Type ..." name='container[1][load_type_id]'>
                    <option value="">Select Container Loading Type ...</option>
                    @foreach ($loadTypes as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                    </select>
                    @error('container.1.load_type_id')
                        <span class='form-text text-danger'>{{ $message }}</span>
                    @enderror
                </div>
            </td>
            <td class="text-center row_action">
                <button type="button" class="btn btn-danger btn-icon btn-sm remove-btn"><i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-warning btn-icon btn-sm add-btn"><i class="fa fa-plus"></i></button>
            </td>
        </tr>
        @endforelse


    </tbody>
</table>
