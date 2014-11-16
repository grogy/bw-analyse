#!/usr/bin/perl -w

use strict;
use Parse::MediaWikiDump;
use utf8;

binmode(STDOUT, ":utf8"); # for correct UTF-8 output in terminal

my $file = shift(@ARGV) or die "must specify a Mediawiki dump file";
my $pages = Parse::MediaWikiDump::Pages->new($file);
my $page;

while(defined($page = $pages->next)) {
    print "Title: ", $page->title, "\n";

    my $text = $page->text;
    print "Text: ", $$text, "\n";

    if (defined($page->categories)) {
        my @cats = @{$page->categories};
        foreach (@cats) {
            print "Kategory: ", $_, "\n";
        }
    }

    print "\n\n\n\n";
}
