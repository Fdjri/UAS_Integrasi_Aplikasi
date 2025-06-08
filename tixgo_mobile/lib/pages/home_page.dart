import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

class HomePage extends StatefulWidget {
  @override
  _HomePageState createState() => _HomePageState();
}

class _HomePageState extends State<HomePage> {
  String selectedCategory = 'hotel';  // Default category
  List services = [];  // List to store service data

  // Fetch services from the API based on the selected category
  Future<void> fetchServices(String category) async {
    final response = await http.get(Uri.parse('http://127.0.0.1:8000/api/services'));

    if (response.statusCode == 200) {
      // Parse the response body and filter the data by category
      List data = jsonDecode(response.body);
      setState(() {
        services = data.where((service) => service['service_type'] == category).toList();
      });
    } else {
      throw Exception('Failed to load services');
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
            child: ListView.builder(
              itemCount: services.length,
              itemBuilder: (context, index) {
                return Card(
                  margin: EdgeInsets.all(10),
                  child: ListTile(
                    leading: Image.asset(
                      'assets/${services[index]['photo']}',  // Assuming photo path is correct
                      width: 100,
                      height: 100,
                    ),
                    title: Text(services[index]['title']),
                    subtitle: Text(services[index]['service_address']),
                    trailing: Icon(Icons.arrow_forward),
                    onTap: () {
                      // Navigate to service details page or take action
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
