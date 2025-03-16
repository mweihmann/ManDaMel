# Use the official MySQL image as the base
FROM mysql:latest

# Set environment variables (These will be overridden by docker-compose)
ENV MYSQL_DATABASE=mandamel
ENV MYSQL_USER=mandamel
ENV MYSQL_PASSWORD=mandamel
ENV MYSQL_ROOT_PASSWORD=mandamel

# Copy the initialization SQL script into the MySQL entrypoint directory
COPY init.sql /docker-entrypoint-initdb.d/

# Expose MySQL port
EXPOSE 3306