<?php

namespace App\Http\Controllers\LMS;

use App\Http\Controllers\Controller;
use App\Models\LMS\Certificate;

class CertificateController extends Controller
{
    public function download(Certificate $cert)
    {
        abort_unless($cert->user_id === auth()->id(), 403);

        return inertia('LMS/Certificates/Show', [
            'certificate' => $cert->load('course', 'user'),
        ]);
    }
}
