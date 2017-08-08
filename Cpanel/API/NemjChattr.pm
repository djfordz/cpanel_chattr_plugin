package Cpanel::API::NemjChattr;

use strict;

our $VERSION = '1.0';

use Cpanel                  ();
use Cpanel::API             ();
use Cpanel::AdminBin::Call  ();
use Cpanel::LoadModule      ();
use Cpanel::Logger          ();
use Data::Dumper            ();

my $logger;

sub _initialize {
    $logger ||= Cpanel::Logger->new();
    return 1;
}

sub get {
    _initialize();
    my ( $args, $result ) = @_;

    my $path = $args->get('path');
    my $val;

        
        $val = Cpanel::AdminBin::Call::call(
            'Nemanja',
            'Chattr',
            'GET',
            $path,
        );

        return $result->data($val);
}

sub enable {
    _initialize();
    my ( $args, $result ) = @_;

    my $path = $args->get('path');
    my $val;

        
    $val = Cpanel::AdminBin::Call::call(
        'Nemanja',
        'Chattr',
        'ENABLE',
        $path,
    );

    return $result->data($val);
}

sub disable {
    _initialize();
    my ( $args, $result ) = @_;

    my $path = $args->get('path');
    my $val;

        
    $val = Cpanel::AdminBin::Call::call(
        'Nemanja',
        'Chattr',
        'DISABLE',
        $path,
    );

    return $result->data($val);
}

1;
