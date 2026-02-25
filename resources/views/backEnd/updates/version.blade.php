@extends('backEnd.layouts.master')

@section('title', 'System Update')

@section('content')
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <h4 class='text-white'>System Update</h4>
        </div>
        <div class="card-body">
            @if(count($updates) > 0)
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="versionSelect">Select Version to Update</label>
                            <select class="form-control" id="versionSelect">
                                <option value="">-- Select Version --</option>
                                @foreach($updates as $update)
                                    <option 
                                        value="{{ $update['version'] }}" 
                                        data-download-url="{{ $update['download_url'] }}"
                                    >
                                        {{ $update['version'] }} - {{ $update['title'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div id="updateDetails" class="mt-4" style="display:none;">
                    <h5>Version: <span id="versionBadge" class="badge bg-success"></span></h5>
                    <p><strong>Release Date:</strong> <span id="releaseDate"></span></p>
                    <p><strong>Description:</strong> <span id="description"></span></p>
                    <p><strong>Changelog:</strong></p>
                    <ul id="changelog"></ul>

                    <div class="form-group mt-3">
                        <label for="licenseKey">Enter License Key</label>
                        <input type="text" id="licenseKey" class="form-control" placeholder="Enter your license key">
                    </div>

                    <button id="runUpdateBtn" class="btn btn-warning mt-3">
                        <i class="fa fa-download"></i> Update Now
                    </button>
                </div>
            @else
                <div class="alert alert-info">No updates found or server unreachable.</div>
            @endif

            <div id="updateMessage" class="mt-3"></div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.getElementById('versionSelect').addEventListener('change', function() {
    const version = this.value;
    if (!version) {
        document.getElementById('updateDetails').style.display = 'none';
        return;
    }

    fetch("{{ route('update.details') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": "{{ csrf_token() }}",
            "Accept": "application/json",
            "Content-Type": "application/json"
        },
        body: JSON.stringify({version: version})
    })
    .then(async res => {
        if (!res.ok) {
            const error = await res.json();
            throw new Error(error.message || 'Failed to fetch details');
        }
        return res.json();
    })
    .then(data => {
        if (data.status === 'success') {
            const update = data.update;

            document.getElementById('versionBadge').textContent = update.version;
            document.getElementById('releaseDate').textContent = update.release_date || 'N/A';
            document.getElementById('description').textContent = update.description || 'No description available';

            const changelog = document.getElementById('changelog');
            changelog.innerHTML = '';
            if (update.changelog && Array.isArray(update.changelog)) {
                update.changelog.forEach(item => {
                    const li = document.createElement('li');
                    li.textContent = item;
                    changelog.appendChild(li);
                });
            } else {
                changelog.innerHTML = '<li>No changelog available</li>';
            }

            document.getElementById('updateDetails').style.display = 'block';
        } else {
            throw new Error(data.message || 'Unknown error');
        }
    })
    .catch(err => {
        console.error("Error:", err);
        alert("Error: " + err.message);
        document.getElementById('updateDetails').style.display = 'none';
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Check if there's an active cooldown
    const lastUpdateTime = localStorage.getItem('lastUpdateTime');
    const cooldownPeriod = 60000; // 1 minute in milliseconds
    
    if (lastUpdateTime && (Date.now() - lastUpdateTime < cooldownPeriod)) {
        const remainingTime = Math.ceil((cooldownPeriod - (Date.now() - lastUpdateTime)) / 1000);
        disableUpdateForm(remainingTime);
    }
});

function disableUpdateForm(seconds) {
    const versionSelect = document.getElementById('versionSelect');
    const runUpdateBtn = document.getElementById('runUpdateBtn');
    const updateMessage = document.getElementById('updateMessage');
    
    versionSelect.disabled = true;
    runUpdateBtn.disabled = true;
    
    let remaining = seconds;
    
    const timerInterval = setInterval(() => {
        remaining--;
        
        if (remaining <= 0) {
            clearInterval(timerInterval);
            versionSelect.disabled = false;
            runUpdateBtn.disabled = false;
            updateMessage.innerHTML = '';
        } else {
            updateMessage.innerHTML = `
                <div class="alert alert-warning">
                    Please wait ${remaining} seconds before attempting another update.
                </div>
            `;
        }
    }, 1000);
}

document.getElementById('runUpdateBtn').addEventListener('click', function () {
    const btn = this;
    const versionSelect = document.getElementById('versionSelect');
    const version = versionSelect.value;
    const downloadUrl = versionSelect.selectedOptions[0].dataset.downloadUrl;
    const licenseKey = document.getElementById('licenseKey').value.trim();

    const msgDiv = document.getElementById('updateMessage');
    msgDiv.innerHTML = '';

    if (!licenseKey) {
        msgDiv.innerHTML = '<div class="alert alert-danger">Please enter your license key.</div>';
        return;
    }

    if (!confirm('Are you sure you want to update? Make sure you have a backup.')) {
        return;
    }

    btn.disabled = true;
    versionSelect.disabled = true;
    btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Verifying...';

    // Validate license key
    fetch("{{ url('https://webleez.top/api/updates/validate-license') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json"
        },
        body: JSON.stringify({
            version: version,
            license_key: licenseKey
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.status !== 'success') {
            throw new Error(data.message || 'License validation failed.');
        }

        // Proceed to run update
        btn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Updating...';

        return fetch("{{ route('update.run') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json",
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                version: version,
                download_url: downloadUrl
            })
        });
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            // Store the current time in localStorage
            localStorage.setItem('lastUpdateTime', Date.now());
            
            // Start countdown
            disableUpdateForm(60); // 60 seconds = 1 minute
            
            msgDiv.innerHTML = `
                <div class="alert alert-success">${data.message}</div>
                <div class="alert alert-warning">
                    Please wait 60 seconds before attempting another update.
                </div>
            `;
        } else {
            msgDiv.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
            versionSelect.disabled = false;
            btn.disabled = false;
        }
        btn.innerHTML = '<i class="fa fa-download"></i> Update Now';
    })
    .catch(err => {
        msgDiv.innerHTML = `<div class="alert alert-danger">Error: ${err.message}</div>`;
        btn.disabled = false;
        versionSelect.disabled = false;
        btn.innerHTML = '<i class="fa fa-download"></i> Update Now';
    });
});
</script>
@endsection
