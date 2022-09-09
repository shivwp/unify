<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
	<div class="card">


<div class="card-body">
    <div class="table-responsive">
        <table class=" table table-bordered table-striped table-hover datatable datatable-Project">
            <thead>
                <tr>
                   
                    <th>
                       #ID
                    </th>
                    <th>
                       Project Name
                    </th>
                    <th>
                    USER
                    </th>
                    <th>
                    TICKET ID
                    </th>
                    <th>
                    SOURCE
                    </th>
                    <th>
                    DATE
                    </th>
                    <th>
                    STATUS
                    </th>
                    
                    
                </tr>
            </thead>
            <tbody>
                @foreach($Support as $key => $item)
                    <tr data-entry-id="{{ $item->id }}">
                       <td> {{ $item->id ?? '' }}</td>
                        <td style="margin-left: 3px;">
                        {{ $item->project->name ?? '' }}
                        </td>
                        <td style="margin-left: 3px;">
                        {{ $item->user->name ?? '' }}
                        </td>
                        <td style="margin-left: 3px;">
                        {{ $item->ticket ?? '' }}
                        </td>
                        <td style="margin-left: 3px;">
                        {{ $item->source ?? '' }}
                        </td>
                        <td style="margin-left: 3px;">
                        {{ date('j \\ F Y', strtotime($item->created_at)) }}
                        </td>
                        <td>{{ $item->status }}</td>
                        

                    </tr>
                @endforeach
            </tbody>
        </table>
      
    </div>
</div>
</div>
</body>
</html>