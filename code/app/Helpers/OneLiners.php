<?php

namespace App\Helpers;

class OneLiners
{
    static private array $oneLiners = [
        "<em>Beer.</em> I like it.",
        "<em>De la cerveza.</em> Me gusta.",
        "<em>Bira.</em> Bunu sevdim.",
        "Nigel's Rockin' Site!",
        "100% <i>electronic.</i>",
        "No electrons were harmed in the making of this site,<br/> however some were slightly inconvenienced.",
        "Imagine if no one had invented handles.",
        "Land ahoy! <b>CRASH!</b> should 'ave said that sooner eh?",
        "SPAM! SPAM! SPAM! SPAM! SPAM!<br/> - Monty Python.",
        "We are the knights that say 'Ni'! - Knights that say 'Ni'",
        "El oso bebe la cerveza! - duolingo",
        "Its not dead, its just pining for the fjords.",
        "The crux of the biscuit is the <b>apostrophe</b>.",
        "Side effects may include: runny nose,<br/>tired eyes, police escort, or a loud swishing noise.",
        "Does not contain gluten or lactose.",
        "Store below 60&deg;C in a dry place.",
        "Now with zircon encrusted tweezers!",
        "My other site is a CMSFUBAR.",
        "You must gather your party before venturing forth.",
        "I used to be an adventurer like you until I took an arrow to the knee.",
        "He aproximado conocimiento de muchas cosas.",
        "This site uses SQL. SHOUTED QUERY LANGUAGE.",
    ];

    static public function getOneLiner()
    {
        return self::$oneLiners[rand(0,count(self::$oneLiners)-1)];
    }
}
