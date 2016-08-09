# PHP-Hex-Viewer
A simple, though impractical hex viewer implemented in PHP.

Example output, from php.exe

```
        00 01 02 03 04 05 06 07   08 09 0A 0B 0C 0D 0E 0F   
        -- -- -- -- -- -- -- --   -- -- -- -- -- -- -- --   
00000 | 4D 5A 90 00 03 00 00 00   04 00 00 00 FF FF 00 00   MZ...... ........ 
00010 | B8 00 00 00 00 00 00 00   40 00 00 00 00 00 00 00   ........ @....... 
00020 | 00 00 00 00 00 00 00 00   00 00 00 00 00 00 00 00   ........ ........ 
00030 | 00 00 00 00 00 00 00 00   00 00 00 00 08 01 00 00   ........ ........ 
00040 | 0E 1F BA 0E 00 B4 09 CD   21 B8 01 4C CD 21 54 68   ........ !..L.!Th 
00050 | 69 73 20 70 72 6F 67 72   61 6D 20 63 61 6E 6E 6F   is progr am canno 
00060 | 74 20 62 65 20 72 75 6E   20 69 6E 20 44 4F 53 20   t be run  in DOS  
00070 | 6D 6F 64 65 2E 0D 0D 0A   24 00 00 00 00 00 00 00   mode.... $....... 
 -- snip --
```

## Features
- Chunked File Processing (brought a 1MB file from 14MB memory peak to around 390kB)
- Output to browser, cli, or file.
- Adjustable chunk sizes
- Adjustable visual splits
- Adjustable EOL

Enjoy.
