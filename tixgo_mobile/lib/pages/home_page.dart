import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:cached_network_image/cached_network_image.dart';
import 'dart:convert';
import 'service_detail_page.dart';  // Import ServiceDetailPage
import '../services/auth_service.dart';  // Import AuthService

class HomePage extends StatefulWidget {
  @override
  _HomePageState createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  String selectedCategory = 'hotel';  // Default category
  List services = [];  // List to store service data

  // Fetch services from the API based on the selected category
  Future<void> fetchServices(String category) async {
    try {
      final authService = AuthService();  // Create an instance of AuthService
      final accessToken = await authService.getToken(); // Get token

      if (accessToken == null || accessToken.isEmpty) {
        print('Token tidak ditemukan');
        return;
      }

      final response = await http.get(
        Uri.parse('http://192.168.1.25:8000/api/services?service_type=$category'),
        headers: {
          'Authorization': 'Bearer $accessToken', // Use the stored token
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 10));

      if (response.statusCode == 200) {
        final Map<String, dynamic> json = jsonDecode(response.body);
        if (json.containsKey('services')) {
          setState(() {
            services = json['services'];  // Assuming API returns data in 'services' field
          });
        } else {
          setState(() {
            services = [];
          });
          print('No services found in response.');
        }
      } else if (response.statusCode == 401) {
        setState(() {
          services = [];
        });
        print('Unauthorized: Invalid or expired token');
        // Optionally, handle token refresh or re-login
      } else {
        setState(() {
          services = [];
        });
        print('Failed to load services: ${response.statusCode}');
        print('Response body: ${response.body}');
      }
    } catch (e) {
      setState(() {
        services = [];
      });
      print('Error fetching services: $e');
    }
  }

  @override
  void initState() {
    super.initState();
    fetchServices(selectedCategory);  // Fetch default category (hotel)
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('TixGo')),
      body: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Filter buttons
          Padding(
            padding: const EdgeInsets.all(8.0),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceAround,
              children: [
                ElevatedButton(
                  onPressed: () {
                    setState(() {
                      selectedCategory = 'hotel';
                    });
                    fetchServices('hotel');
                  },
                  child: Text('Hotel'),
                ),
                ElevatedButton(
                  onPressed: () {
                    setState(() {
                      selectedCategory = 'transportasi';
                    });
                    fetchServices('transportasi');
                  },
                  child: Text('Transportasi'),
                ),
                ElevatedButton(
                  onPressed: () {
                    setState(() {
                      selectedCategory = 'event';
                    });
                    fetchServices('event');
                  },
                  child: Text('Event'),
                ),
              ],
            ),
          ),

          // Displaying the selected category services
          Expanded(
            child: services.isEmpty
                ? Center(child: CircularProgressIndicator()) // Show loading indicator
                : ListView.builder(
                    itemCount: services.length,
                    itemBuilder: (context, index) {
                      return Card(
                        margin: EdgeInsets.all(10),
                        child: ListTile(
                          leading: SizedBox(
                            width: 100,  // Fixed width for the image
                            height: 100, // Fixed height for the image
                            child: CachedNetworkImage(
                              imageUrl: services[index]['photo_url'],  // Assuming photo_url is a valid URL
                              width: 100,
                              height: 100,
                              fit: BoxFit.cover,
                              placeholder: (context, url) => CircularProgressIndicator(), // Show a loading indicator while the image is loading
                              errorWidget: (context, url, error) => Icon(Icons.error), // Show an error icon if the image fails to load
                            ),
                          ),
                          title: Text(services[index]['title']),
                          subtitle: Text(services[index]['service_address'] ?? 'No Address'),
                          trailing: Icon(Icons.arrow_forward),
                          onTap: () {
                            Navigator.push(
                              context,
                              MaterialPageRoute(
                                builder: (context) => ServiceDetailPage(service: services[index]),
                              ),
                            );
                          },
                        ),
                      );
                    },
                  ),
          ),
        ],
      ),
    );
  }
}
