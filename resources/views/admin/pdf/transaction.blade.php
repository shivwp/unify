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
                    Transaction Type
                    </th>
                    <th>
                    Income Source
                    </th>
                    <th>
                    Amount
                    </th>
                    <th>
                    Currency
                    </th>
                    
                    
                </tr>
            </thead>
            <tbody>
                @foreach($transaction as $key => $project)
                    <tr data-entry-id="{{ $project->id }}">
                       <td>{{$project->id}}</td>
                        <td style="margin-left: 3px;">
                        {{ $project->project->name ?? '' }}
                        </td>
                        <td style="margin-left: 3px;">
                        {{ $project->transaction_type->name ?? '' }}
                        </td>
                        <td style="margin-left: 3px;">
                        {{ $project->income_source->name ?? '' }}
                        </td>
                        <td style="margin-left: 3px;">
                        {{ $project->amount ?? '' }}
                        </td>
                        <td style="margin-left: 3px;">
                        {{ $project->currency->name ?? '' }}
                        </td>
                        
                        

                    </tr>
                @endforeach
            </tbody>
        </table>
      
    </div>
</div>
</div>
</body>
</html>