package com.jobportal.controller;

import com.jobportal.entity.Job;
import com.jobportal.entity.User;
import com.jobportal.service.ApplicationService;
import com.jobportal.service.JobService;
import com.jobportal.service.UserService;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.multipart.MultipartFile;
import org.springframework.data.domain.Page;

import java.io.IOException;
import java.math.BigDecimal;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.security.Principal;

@Controller
@RequestMapping("/student")
public class StudentController {
    private final UserService userService;
    private final JobService jobService;
    private final ApplicationService applicationService;

    public StudentController(UserService userService, JobService jobService, ApplicationService applicationService) {
        this.userService = userService;
        this.jobService = jobService;
        this.applicationService = applicationService;
    }

    @GetMapping("/dashboard")
    public String dashboard(Model model, Principal principal) {
        User student = userService.findByEmail(principal.getName());
        model.addAttribute("student", student);
        model.addAttribute("jobs", jobService.getAllJobs());
        model.addAttribute("applications", applicationService.byStudent(student));
        model.addAttribute("totalApplications", applicationService.countApplicationsByStudent(student));
        model.addAttribute("appliedCount", applicationService.countByStudentAndStatus(student, com.jobportal.entity.ApplicationStatus.APPLIED));
        model.addAttribute("shortlistedCount", applicationService.countByStudentAndStatus(student, com.jobportal.entity.ApplicationStatus.SHORTLISTED));
        model.addAttribute("rejectedCount", applicationService.countByStudentAndStatus(student, com.jobportal.entity.ApplicationStatus.REJECTED));
        model.addAttribute("role", "STUDENT");
        model.addAttribute("pageTitle", "Student Dashboard");
        return "student-dashboard-v2";
    }

    @GetMapping("/jobs")
    public String jobs(
            Model model,
            @RequestParam(required = false) String category,
            @RequestParam(required = false) String location,
            @RequestParam(required = false) String experience,
            @RequestParam(required = false) String skills,
            @RequestParam(required = false) String minSalary,
            @RequestParam(required = false) String maxSalary,
            @RequestParam(defaultValue = "0") int page,
            @RequestParam(defaultValue = "9") int size
    ) {
        BigDecimal min = parseBigDecimal(minSalary);
        BigDecimal max = parseBigDecimal(maxSalary);
        Page<Job> jobPage = jobService.searchPaged(category, location, experience, skills, min, max, page, size);
        model.addAttribute("jobPage", jobPage);
        model.addAttribute("jobs", jobPage.getContent());
        model.addAttribute("page", page);
        model.addAttribute("size", size);
        model.addAttribute("totalPages", jobPage.getTotalPages());
        model.addAttribute("role", "STUDENT");
        model.addAttribute("pageTitle", "Job Listings");
        model.addAttribute("category", category);
        model.addAttribute("location", location);
        model.addAttribute("experience", experience);
        model.addAttribute("skills", skills);
        model.addAttribute("minSalary", min);
        model.addAttribute("maxSalary", max);
        return "job-list";
    }

    @GetMapping("/applications")
    public String applications(Model model, Principal principal) {
        User student = userService.findByEmail(principal.getName());
        model.addAttribute("applications", applicationService.byStudent(student));
        model.addAttribute("role", "STUDENT");
        model.addAttribute("pageTitle", "Application Tracking");
        return "applications";
    }

    @GetMapping("/jobs/{jobId}")
    public String jobDetails(@PathVariable Long jobId, Model model) {
        model.addAttribute("job", jobService.getById(jobId));
        model.addAttribute("role", "STUDENT");
        model.addAttribute("pageTitle", "Job Details");
        return "job-details";
    }

    private BigDecimal parseBigDecimal(String value) {
        if (value == null) return null;
        String trimmed = value.trim();
        if (trimmed.isEmpty()) return null;
        return new BigDecimal(trimmed);
    }

    @PostMapping("/profile")
    public String updateProfile(@RequestParam String name,
                                @RequestParam String skills,
                                @RequestParam String experience,
                                @RequestParam("resume") MultipartFile resume,
                                Principal principal) throws IOException {
        User student = userService.findByEmail(principal.getName());
        student.setName(name);
        student.setSkills(skills);
        student.setExperience(experience);

        if (!resume.isEmpty()) {
            Path uploadDir = Paths.get("src/main/resources/uploads");
            Files.createDirectories(uploadDir);
            Path target = uploadDir.resolve(student.getId() + "_" + resume.getOriginalFilename());
            Files.write(target, resume.getBytes());
            student.setResumePath(target.toString());
        }

        userService.save(student);
        return "redirect:/student/dashboard";
    }

    @PostMapping("/jobs/{jobId}/apply")
    public String apply(@PathVariable Long jobId, Principal principal) {
        User student = userService.findByEmail(principal.getName());
        Job job = jobService.getById(jobId);
        applicationService.apply(student, job);
        return "redirect:/student/dashboard";
    }
}
