# LED Project

```
Build bellow API thanks to :
- Lumen as PHP Backend
- DynamoDB local for persistance
- At least on php unit test
- Swagger to document
*API Contract*
[GET]   	/led  				=> List all LED
[POST]		/led 				=> Add a new LED
[GET]   	/led/{ledId}		=> Display information of a specific LED (except Color)
[DELETE]	/led/{ledId}		=> Delete a specific LED
[PUT]  		/led/{ledId} 		=> Update information of a LED (except Color)
[GET]		/led/{ledId}/color  => Get color of a led
[PUT]		/led/{ledId}/color 	=> Update color of a led
LED Object
{
	"id"   : (string) "UUIDv4",
	"name" : (string) "name of the led",
	"lastUpdate" : (int) timestamp
}
COLOR Object
{
	"red" : int(0-255)
	"green" : int(0-255)
	"blue" : int(0-255)
}
```
